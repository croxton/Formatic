using System.Text;
using io = System.IO;

namespace wpf_generator.Helpers
{
    public static class RegionInfoParser
    {
        public static string GetFromFile(string filename)
        {
            if (!io::File.Exists(filename))
                return string.Empty;

            using (var input = new io::StreamReader(filename, Encoding.UTF8))
            {
                string line;
                var regionInfo = new StringBuilder();
                bool outputFlag = false;
                while((line = input.ReadLine()) != null)
                {
                    if (line.Trim('\t', ' ').ToLower().StartsWith("$.formatcurrency.region"))
                        outputFlag = true;

                    if (outputFlag)
                    {
                        regionInfo.AppendLine(line);

                        if (line.Trim(' ', '\t').StartsWith("};"))
                            break;
                    }
                }
                return regionInfo.ToString();
            }
        }
    }
}