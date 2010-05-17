using System.IO;

namespace wpf_generator.Helpers
{
    public class DocumentWriter
    {
        public static string License;

        public static void WriteJQueryHeader(TextWriter writer)
        {
            writer.WriteLine("(function($) {");
            writer.WriteLine();
        }

        public static void WriteJQueryFooter(TextWriter writer)
        {
            writer.WriteLine("})(jQuery);");
        }

        public static void WriteLicense(TextWriter writer)
        {
            writer.WriteLine(License);
            writer.WriteLine();
        }
    }
}
