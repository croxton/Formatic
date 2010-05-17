using System.Collections.Generic;
using System.Globalization;
using wpf_generator.Models;

namespace wpf_generator.Helpers
{
    public static class Localization
    {
        private static string[] countryCodes = new string[]
                                                   {
                                                       "AE",
                                                       "AL",
                                                       "AM",
                                                       "AR",
                                                       "AT",
                                                       "AU",
                                                       "AZ",
                                                       "BE",
                                                       "BG",
                                                       "BH",
                                                       "BN",
                                                       "BO",
                                                       "BR",
                                                       "BY",
                                                       "BZ",
                                                       "CA",
                                                       "CH",
                                                       "CL",
                                                       "CN",
                                                       "CO",
                                                       "CR",
                                                       "CZ",
                                                       "DE",
                                                       "DK",
                                                       "DO",
                                                       "DZ",
                                                       "EC",
                                                       "EE",
                                                       "EG",
                                                       "ES",
                                                       "FI",
                                                       "FO",
                                                       "FR",
                                                       "GB",
                                                       "GE",
                                                       "GR",
                                                       "GT",
                                                       "HK",
                                                       "HN",
                                                       "HR",
                                                       "HU",
                                                       "ID",
                                                       "IE",
                                                       "IL",
                                                       "IN",
                                                       "IQ",
                                                       "IR",
                                                       "IS",
                                                       "IT",
                                                       "JM",
                                                       "JO",
                                                       "JP",
                                                       "KE",
                                                       "KG",
                                                       "KR",
                                                       "KW",
                                                       "KZ",
                                                       "LB",
                                                       "LI",
                                                       "LT",
                                                       "LU",
                                                       "LV",
                                                       "LY",
                                                       "MA",
                                                       "MC",
                                                       "MK",
                                                       "MN",
                                                       "MO",
                                                       "MV",
                                                       "MX",
                                                       "MY",
                                                       "NI",
                                                       "NL",
                                                       "NO",
                                                       "NZ",
                                                       "OM",
                                                       "PA",
                                                       "PE",
                                                       "PH",
                                                       "PK",
                                                       "PL",
                                                       "PR",
                                                       "PT",
                                                       "PY",
                                                       "QA",
                                                       "RO",
                                                       "RU",
                                                       "SA",
                                                       "SE",
                                                       "SG",
                                                       "SI",
                                                       "SK",
                                                       "SV",
                                                       "SY",
                                                       "TH",
                                                       "TN",
                                                       "TR",
                                                       "TT",
                                                       "TW",
                                                       "UA",
                                                       "US",
                                                       "UY",
                                                       "UZ",
                                                       "VE",
                                                       "VN",
                                                       "YE",
                                                       "ZA",
                                                       "ZW"
                                                   };

        public static List<CountryInfo> CountryList
        {
            get
            {
                var Countries = new List<CountryInfo>();
                foreach (var countryCode in countryCodes)
                {
                    RegionInfo x = new RegionInfo(countryCode);
                    if (x.EnglishName.Equals(x.NativeName))
                    {
                        var ci = new CountryInfo { Code = x.TwoLetterISORegionName, Country=x.EnglishName };
                        Countries.Add(ci);
                    }
                    else
                    {
                        var ci = new CountryInfo { Code = x.TwoLetterISORegionName, Country = string.Format("{0} - {1}", x.EnglishName, x.NativeName) };
                        Countries.Add(ci);
                    }
                }
                return Countries;
            }
        }
        private static List<CountryInfo> _cultures;

        
        private static Dictionary<string, string> _SupportedLanguages;

        public static Dictionary<string, string> SupportedLanguages
        {
            get
            {
                if (_SupportedLanguages == null)
                {
                    _SupportedLanguages = new Dictionary<string, string>();
                    foreach (CultureInfo ci in CultureInfo.GetCultures(CultureTypes.AllCultures))
                    {
                        if (!_SupportedLanguages.ContainsKey(ci.TwoLetterISOLanguageName))
                        {
                            if (ci.NativeName.Equals(ci.EnglishName))
                            {
                                _SupportedLanguages.Add(ci.Name, ci.EnglishName);
                            }
                            else
                            {
                                _SupportedLanguages.Add(ci.TwoLetterISOLanguageName,
                                                        string.Format("{0} - {1}", ci.EnglishName, ci.NativeName));
                            }
                        }
                    }
                }
                return _SupportedLanguages;
            }
        }
        public static List<CountryInfo> Cultures
        {
            get
            {
                if (_cultures == null)
                {
                    _cultures = new List<CountryInfo>();
                    // Enumerate through all the languages .NET may be localized into
                    foreach (CultureInfo ci in CultureInfo.GetCultures(CultureTypes.SpecificCultures))
                    {
                        if (ci.NativeName.Equals(ci.EnglishName))
                        {
                            var c = new CountryInfo { Code = ci.Name, Country=ci.EnglishName, Currency=1000.ToString("c", ci.NumberFormat) };
                            _cultures.Add(c);
                        }
                        else
                        {
                            var c = new CountryInfo { Code = ci.Name, Country = string.Format("{0} - {1}", ci.EnglishName, ci.NativeName), Currency = 1000.ToString("c", ci.NumberFormat) };
                            _cultures.Add(c);
                        }

                    }
                }
                return _cultures;
            }
        }

        private static string currencyFormatPattern(
            decimal value,
            NumberFormatInfo format)
        {
            var formatPattern = value.ToString("c", format);
            formatPattern = formatPattern.Replace(format.CurrencySymbol, "%s");
            var num = System.Math.Abs(value).ToString("c", format);
            num = num.Replace(format.CurrencySymbol, "").Trim(' ');
            formatPattern = formatPattern.Replace(num, "%n");
            return formatPattern;
        }

        public static string CurrencyFormat(NumberFormatInfo numberFormat)
        {
            string positiveValue = currencyFormatPattern(5.111m, numberFormat);
            string negativeValue = currencyFormatPattern(-5.111m, numberFormat);

            string groupDigits = numberFormat.CurrencyGroupSizes.Length != 0 ? "true" : "false";

            return string.Format("{{\n" +
                                 "\t\tsymbol: '{0}',\n" +
                                 "\t\tpositiveFormat: '{1}',\n" +
                                 "\t\tnegativeFormat: '{2}',\n" +
                                 "\t\tdecimalSymbol: '{3}',\n" +
                                 "\t\tdigitGroupSymbol: '{4}',\n" +
                                 "\t\tgroupDigits: {5}\n" +
                                 "\t}}", numberFormat.CurrencySymbol.Replace("'", "\\'"), positiveValue, negativeValue,
                                 numberFormat.CurrencyDecimalSeparator.Replace("'", "\\'"), numberFormat.CurrencyGroupSeparator.Replace("'", "\\'"), 
                                 groupDigits);
        }

    }
}