using System.Windows;

namespace wpf_generator.Models
{
    public class CountryInfo : DependencyObject
    {
        public CountryInfo()
        {
            IsSelected = true;
        }

        public bool IsSelected
        {
            get { return (bool)GetValue(IsSelectedProperty); }
            set { SetValue(IsSelectedProperty, value); }
        }

        // Using a DependencyProperty as the backing store for IsSelected.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty IsSelectedProperty =
            DependencyProperty.Register("IsSelected", typeof(bool), typeof(CountryInfo), new UIPropertyMetadata(true));

        public string Code { get; set; }
        public string Country { get; set; }
        public string Currency { get; set; }
    }
}