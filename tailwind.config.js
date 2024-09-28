/** @type {import('tailwindcss').Config} config */
const config = {
  content: ['./index.php', './app/**/*.php', './resources/**/*.{php,vue,js}'],
  theme: {
    fontFamily: {
      Inter: ['Inter', 'sans-serif'],
      Oswald: ['Oswald', 'sans-serif'],
    },
    extend: {
      backgroundImage: {
        'menu-arrow-down': "url('/images/icons/arrow-down.svg')",
        'arrow-right': "url('/images/icons/arrow-right.svg')",
        'arrow-left': "url('/images/icons/arrow-left.svg')",
        'menu-arrow-image': "url('/images/icons/add.svg')",
        'delete-icon': "url('/images/icons/add.svg')",
        'table-size': "url('/images/ruler.svg')",
        'cart-icon': "url('/images/cart-icon.svg')",

      },
        fontSize: {
            '12': ['.75rem', '20px'], //12px
            '13': ['.8125rem', '20px'], //13px
            '14': ['.875rem', '22px'], //14px
            '15': ['.938rem', '24px'], //15px
            'base': ['1rem', '28px'], //16px
            '17': ['1.063rem', '24px'], //17px
            '18': ['1.125rem', '28px'], //18px
            '19': ['1.188rem', '32px'], //19px
            '20': ['1.25rem', '28px'], //20px
            '22': ['1.375rem', '34px'], //22px
            '24': ['1.5rem', '34px'], //22px
            '26': ['1.625rem', '34px'], //26px
            '28': ['1.75rem', '36px'], //28px
            '32': ['2rem', '40px'], //32px
            '36': ['2.25rem', '42px'], //36px
            '38': ['2.375rem', '38px'], //38px
            '40': ['2.5rem', '38px'], //40px
            '60': ['3.75rem', '64px'], //60px
            'h3': ['2.375rem', '40px'], //38px
            'h2': ['2.875rem', '56px'], //46px
            'h1': ['3.875rem', '86px'], //66px
        },
        container: {
            center: true,
            screens: {
              '2xl': '1480px',
          },
        },

        zIndex: {
          '60': '60',
          '70': '70',
          '80': '80',
          '90': '90',
          '100': '100',
        },

        colors: {
          'main': '#0095DA',
          'second': '#ED1C24',
          'third': '#FFCB05',
          'gray': '#F3F3F3',
          'box': '#F4F1EB',
          'third': '#FEB93B',
          'detail': '#173945',
          'label': '#787878',
          'border': '#D7D7D7',
          'button-hover': '#000000',
          'green': '#439945',
        },
        borderRadius: {
          '4xl': '2rem',
        },
        animation: {
          marquee: 'marquee 25s linear infinite',
          marquee2: 'marquee2 25s linear infinite',
        },
        keyframes: {
          marquee: {
            '0%': { transform: 'translateX(0%)' },
            '100%': { transform: 'translateX(-100%)' },
          },
          marquee2: {
            '0%': { transform: 'translateX(100%)' },
            '100%': { transform: 'translateX(0%)' },
          },
        },
    }
  },
  plugins: [],
};

export default config;
