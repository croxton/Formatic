using System.Windows;
using wpf_generator.Helpers;

namespace wpf_generator.Models
{
    public class LicenseModel : DependencyObject
    {
        public string LicenseInfo
        {
            get { return (string)GetValue(LicenseInfoProperty); }
            set { SetValue(LicenseInfoProperty, value); }
        }

        // Using a DependencyProperty as the backing store for LicenseInfo.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty LicenseInfoProperty =
            DependencyProperty.Register("LicenseInfo", typeof(string), typeof(LicenseModel), new UIPropertyMetadata(string.Empty, LicenseChanged));

        private static void LicenseChanged(DependencyObject d, DependencyPropertyChangedEventArgs e)
        {
            DocumentWriter.License = e.NewValue.ToString();
        }
    }
}
