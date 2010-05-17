using System;
using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Input;

namespace wpf_generator.Models
{
    public class MergeModel : DependencyObject
    {
        public ICommand BrowseCommand { get; set; }
        public ICommand BrowseOutputFileCommand { get; set; }
        public ICommand MergeCommand { get; set; }

        public event EventHandler InputFolderChanged;

        public ObservableCollection<RegionFileInfo> Files
        {
            get { return (ObservableCollection<RegionFileInfo>)GetValue(FilesProperty); }
            set { SetValue(FilesProperty, value); }
        }

        // Using a DependencyProperty as the backing store for Files.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty FilesProperty =
            DependencyProperty.Register("Files", typeof(ObservableCollection<RegionFileInfo>), typeof(MergeModel), new UIPropertyMetadata(null));




        public string InputFolder
        {
            get { return (string)GetValue(InputFolderProperty); }
            set { SetValue(InputFolderProperty, value); }
        }

        // Using a DependencyProperty as the backing store for InputFile.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty InputFolderProperty =
            DependencyProperty.Register("InputFolder", typeof(string), typeof(MergeModel), new UIPropertyMetadata(string.Empty, InputFolderPropertyChanged));

        private static void InputFolderPropertyChanged(DependencyObject d, DependencyPropertyChangedEventArgs e)
        {
            var mm = d as MergeModel;
            if (mm == null) 
                return;

            mm.RaiseInputFolderChanged();
        }

        public void RaiseInputFolderChanged()
        {
            if (InputFolderChanged == null) return;
            InputFolderChanged(this, EventArgs.Empty);
        }


        public string OutputFile
        {
            get { return (string)GetValue(OutputFileProperty); }
            set { SetValue(OutputFileProperty, value); }
        }

        // Using a DependencyProperty as the backing store for OutputFile.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty OutputFileProperty =
            DependencyProperty.Register("OutputFile", typeof(string), typeof(MergeModel), new UIPropertyMetadata(string.Empty));




        public bool AddLicense
        {
            get { return (bool)GetValue(AddLicenseProperty); }
            set { SetValue(AddLicenseProperty, value); }
        }

        // Using a DependencyProperty as the backing store for AddLicense.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty AddLicenseProperty =
            DependencyProperty.Register("AddLicense", typeof(bool), typeof(MergeModel), new UIPropertyMetadata(true));


    }
}

