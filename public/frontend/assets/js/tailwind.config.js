tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                rethink: ['"Rethink Sans"', 'sans-serif'],
                inter: ['Inter', 'sans-serif'],
                bebas: ['"Bebas Neue"', 'sans-serif'],
            },
            fontSize: {
                xs: ['11px', '16px'],   // font-size 11px, line-height 16px
                sm: ['13px', '20px'],   // font-size 13px, line-height 20px
                base: ['15px', '22px'], // font-size 15px, line-height 22px
            },
            colors: {
                // gray: {
                //     primary: '#0B0B0B',
                //     secondary: '#232323',
                // },
                primary: "#0B0B0B",
                yellow: {
                    100: "#FFE172",
                    200: "#E8E113",
                    300: "#FFE9AD",
                    400: "#F8C026",
                    500: "#523E06",
                },
                orange: {
                    100: "#FFAD72",
                },
                blue: {
                    100: "#72B2FF",
                    200: "#13EFD9",
                },
                purple: {
                    100: "#7659F9",
                },
                fuchsia: {
                    50:  '#fdf4ff',
                    100: '#fae8ff',
                    200: '#f5d0fe',
                    300: '#f0abfc',
                    400: '#e879f9',
                    500: '#d946ef',
                    600: '#CD1AFF',
                    700: '#a21caf',
                    800: '#86198f',
                    900: '#701a75',
                },
                green: {
                    100: "#28B700",
                    900: "#072200",
                    950: "#1A2200",
                },
                teal: {
                    900: "#05393A",
                },
                red: {
                    100: "#B70000",
                    900: "#3B0505",
                },
                coffee: {
                    500: "#523E06",
                },
                dark: {
                    DEFAULT: "#282828",
                },
                gray: {
                    50:  "#F2F2F2",
                    75:  "#EBEBEB",
                    100: "#EAEAEA",
                    200: "#A4A4A4",
                    250: "#9E9B9B",
                    275: "#989898",
                    280: "#808080",
                    300: "#717171",
                    400: "#4B4848",
                    500: "#323334",
                    510: "#323232",
                    600: "#282828",
                    700: "#232323",
                    800: "#202020",
                    850: "#212020",
                    900: "#191919",
                    910: "#343434",
                    925: "#1C1C1C",
                    950: "#181A1C",
                    975: "#161616",
                    990: "#131313",
                    1000:"#0F1012",
                },
                boxShadow: {
                    1: "0px 29px 14px -14px rgba(0,0,0,0.25)", // 25% opacity hitam
                },
            }
        }
    }
}