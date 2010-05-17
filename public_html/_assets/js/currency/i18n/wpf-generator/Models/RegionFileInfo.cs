using System.Windows;

namespace wpf_generator.Models
{
    public class RegionFileInfo : DependencyObject
    {
        public string Filename { get; set; }

        private string fullFilename;
        public string FullFilename
        {
            get { return fullFilename; }
            set
            {
                fullFilename = value;
                Filename = (new System.IO.FileInfo(fullFilename)).Name;
            }
        }

        public bool IsSelected
        {
            get { return (bool)GetValue(IsSelectedProperty); }
            set { SetValue(IsSelectedProperty, value); }
        }

        // Using a DependencyProperty as the backing store for IsSelected.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty IsSelectedProperty =
            DependencyProperty.Register("IsSelected", typeof(bool), typeof(RegionFileInfo), new UIPropertyMetadata(true));


    }
}