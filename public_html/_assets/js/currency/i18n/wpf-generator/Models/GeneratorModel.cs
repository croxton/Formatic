using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Data;
using System.Windows.Input;

namespace wpf_generator.Models
{
    public class GeneratorModel : DependencyObject
    {
        public GeneratorModel()
        {
            ObsCultures = new ObservableCollection<CountryInfo>();
            Cultures = new CollectionViewSource();
        }

        public ICommand BrowseCommand { get; set; }
        public ICommand RunCommand { get; set; }
        public ICommand ToListCommand { get; set; }
        public ICommand SortCommand { get; set; }

        public CollectionViewSource Cultures
        {
            get; set;
        }

        public ObservableCollection<CountryInfo> ObsCultures
        {
            get { return (ObservableCollection<CountryInfo>)GetValue(ObsCulturesProperty); }
            set { SetValue(ObsCulturesProperty, value); }
        }

        // Using a DependencyProperty as the backing store for ObsCultures.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty ObsCulturesProperty =
            DependencyProperty.Register("ObsCultures", typeof(ObservableCollection<CountryInfo>), typeof(GeneratorModel), new UIPropertyMetadata(null));



        public string OutputFolder
        {
            get { return (string)GetValue(OutputFolderProperty); }
            set { SetValue(OutputFolderProperty, value); }
        }

        // Using a DependencyProperty as the backing store for OutputFolder.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty OutputFolderProperty =
            DependencyProperty.Register("OutputFolder", typeof(string), typeof(GeneratorModel), new UIPropertyMetadata(string.Empty));

        public bool ShouldAppendLicense
        {
            get { return (bool)GetValue(ShouldAppendLicenseProperty); }
            set { SetValue(ShouldAppendLicenseProperty, value); }
        }

        // Using a DependencyProperty as the backing store for ShouldAppendLicense.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty ShouldAppendLicenseProperty =
        DependencyProperty.Register("ShouldAppendLicense", typeof(bool), typeof(GeneratorModel), new UIPropertyMetadata(true));



        public bool SelectAll
        {
            get { return (bool)GetValue(SelectAllProperty); }
            set { SetValue(SelectAllProperty, value); }
        }

        // Using a DependencyProperty as the backing store for SelectAll.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty SelectAllProperty =
            DependencyProperty.Register("SelectAll", typeof(bool), typeof(GeneratorModel), new UIPropertyMetadata(true, SelectAllChanged));

        private static void SelectAllChanged(DependencyObject d, DependencyPropertyChangedEventArgs e)
        {
            var gm = d as GeneratorModel;
            if (gm == null) return;

            foreach (var c in gm.ObsCultures)
            {
                c.IsSelected = gm.SelectAll;
            }
        }
    }
}