using System;
using System.Collections.ObjectModel;
using System.IO;
using System.Text;
using System.Windows;
using System.Windows.Threading;
using wpf_generator.Helpers;
using wpf_generator.Models;

using forms = System.Windows.Forms;
using io = System.IO;

namespace wpf_generator.ViewModels
{
    public class MergeViewModel : DependencyObject
    {
        public MergeModel Model { get; private set; }

        public MergeViewModel()
        {
            Model = new MergeModel();

            Model.BrowseCommand = new RelayCommand<object>(BrowseExecute);
            Model.BrowseOutputFileCommand = new RelayCommand<object>(BrowseOutputExecute);
            Model.MergeCommand = new RelayCommand<object>(MergeExecute);

            var path = "../";
#if DEBUG
            path = "../../../";
#endif

            var i18nFolder = new Uri(new Uri(AppDomain.CurrentDomain.BaseDirectory), path).LocalPath;
            Model.OutputFile = io::Path.Combine(i18nFolder, "jquery.formatCurrency.all.js");
            
            // I'm changing the InputFolder shortly so I'll call LoadFiles in a second.
            Model.InputFolderChanged += (s, e) => LoadFiles();
            Model.InputFolder = i18nFolder;
        }

        private FileSystemWatcher folderWatcher;
        private void SetupFolderWatcher()
        {
            if (folderWatcher != null)
            {
                folderWatcher.EnableRaisingEvents = false;
            }
            
            folderWatcher = new FileSystemWatcher(Model.InputFolder);
            folderWatcher.Created += (s, e) => LoadFiles();
            folderWatcher.Deleted += (s, e) => LoadFiles();
            folderWatcher.Renamed += (s, e) => LoadFiles();
            folderWatcher.EnableRaisingEvents = true;
            
        }

        private void LoadFiles()
        {
            if (!this.CheckAccess())
            {
                Dispatcher.Invoke(new Action(LoadFiles), DispatcherPriority.Background);
                return;
            }

            if (Model.Files == null)
                Model.Files = new ObservableCollection<RegionFileInfo>();

            Model.Files.Clear();

            if (!io::Directory.Exists(Model.InputFolder))
                return;

            foreach (var file in io::Directory.GetFiles(Model.InputFolder))
            {
                if (file.EndsWith("all.js")) continue;
                if (!file.EndsWith(".js")) continue;

                Model.Files.Add(new RegionFileInfo { FullFilename = file });
            }

            SetupFolderWatcher();
        }


        private void BrowseExecute(object obj)
        {
            var fd = new forms::FolderBrowserDialog();
            fd.SelectedPath = Model.InputFolder;
            if (fd.ShowDialog() == forms::DialogResult.OK)
            {
                Model.InputFolder = fd.SelectedPath;
            }
        }

        private void BrowseOutputExecute(object obj)
        {
            var fd = new forms::SaveFileDialog();
            fd.FileName = Model.OutputFile;
            if (fd.ShowDialog() == forms::DialogResult.OK)
            {
                Model.OutputFile = fd.FileName;
            }
        }

        private void MergeExecute(object parameter)
        {
            var fi = new io::FileInfo(Model.OutputFile);
            if (fi.Exists)
            {
                if (MessageBox.Show("File already exists. Overwrite?", "Overwrite?", MessageBoxButton.YesNo) == MessageBoxResult.Yes)
                {
                    fi.Delete();
                }
                else
                {
                    return;
                }
            }

            using (var outputFile = new StreamWriter(fi.FullName, false, Encoding.UTF8))
            {
                if (Model.AddLicense)
                    DocumentWriter.WriteLicense(outputFile);

                DocumentWriter.WriteJQueryHeader(outputFile);

                foreach (var file in Model.Files)
                {
                    if (!file.IsSelected)
                        continue;

                    outputFile.WriteLine(RegionInfoParser.GetFromFile(file.FullFilename));
                }

                DocumentWriter.WriteJQueryFooter(outputFile);
            }
            MessageBox.Show("File's done");
        }
    }
}
