using System;
using wpf_generator.Models;
using io = System.IO;

namespace wpf_generator.ViewModels
{
    public class LicenseViewModel
    {
        public LicenseModel Model { get; private set; }

        public LicenseViewModel()
        {
            Model = new LicenseModel();

            var licenseFile = io::Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "LicenseHeader.txt");
            LoadLicense(licenseFile);
        }

        private void LoadLicense(string file)
        {
            using (var license = io::File.OpenText(file))
            {
                Model.LicenseInfo = license.ReadToEnd();
            }
        }
    }
}
