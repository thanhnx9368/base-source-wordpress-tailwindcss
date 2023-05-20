tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                'default-inter': ['Inter', 'ui-sans-serif']
            },
            fontSize: {
                //Font size / LineHeight
                'fs-10': ['0.625rem', '1rem'], //10px/16px
                'fs-12': ['0.75rem', '1rem'], //12px/16px
                'fs-13': ['13px', '22px'], //12px/16px
                'fs-14': ['0.875rem', '1.25rem'], //14px/20px
                base: ['1rem', '1.5rem'], //16px/24px20px
                'fs-16': ['1rem', '1.5rem'], //16px/24px
                'fs-18': ['1.125rem', '1.625rem'], //18px/26px
                'fs-20': ['1.25rem', '1.75rem'], //20px/28px
                'fs-24': ['1.5rem', '2rem'], //24px/32px
                'fs-32': ['2rem', '2.75rem'], //32px/44x
                'fs-36': ['2.25rem', '3rem'], //36px/48px
                'fs-48': ['3rem', '3.75rem'], //48px/60px
                'fs-64': ['4rem', '5rem'] //64px/80px
            },
            colors: {
                primary: {
                    50: 'var(--color-primary-50)',
                    100: 'var(--color-primary-100)',
                    200: 'var(--color-primary-200)',
                    300: 'var(--color-primary-300)',
                    400: 'var(--color-primary-400)',
                    500: 'var(--color-primary-500)',
                    700: 'var(--color-primary-700)'
                },
                secondary: {
                    50: 'var(--color-secondary-50)',
                    200: 'var(--color-secondary-200)',
                    300: 'var(--color-secondary-300)',
                    500: 'var(--color-secondary-500)',
                    600: 'var(--color-secondary-600)'
                },
                grey: {
                    50: 'var(--color-grey-50)',
                    100: 'var(--color-grey-100)',
                    200: 'var(--color-grey-200)',
                    300: 'var(--color-grey-300)',
                    400: 'var(--color-grey-400)',
                    500: 'var(--color-grey-500)',
                    600: 'var(--color-grey-600)',
                    700: 'var(--color-grey-700)',
                    800: 'var(--color-grey-800)',
                    900: 'var(--color-grey-900)'
                },
                success: {
                    500: 'var(--color-success-500)'
                },
                red: {
                    50: 'var(--color-red-50)',
                    100: 'var(--color-red-100)',
                    300: 'var(--color-red-300)',
                    500: 'var(--color-red-500)'
                },
                'dark-gray': 'var(--color-bg-dark-gray)',
                'light-gray': 'var(--color-bg-light-gray)',
                'dark-gray': 'var(--color-bg-dark-gray)',
                'light-blue': 'var(--color-bg-light-blue)',
                'dark-blue': 'var(--color-bg-dark-blue)'
            },
            boxShadow: {
                divide: 'inset 0px -1px 0px #ECECEC',
                header: '0px 0.5px 0px #EEEEEE',
                preview: '0px 0px 40px rgba(0, 0, 0, 0.06)',
                none: 'none',
                'top-divide': '0px 0px 20px rgba(0, 0, 0, 0.2)',
                button: '0px 0px 10px rgba(0, 0, 0, 0.2)',
                comment: '0px 0px 10px rgba(0, 0, 0, 0.2)',
                full: '0px 5px 15px 1px rgba(0, 0, 0, 0.1)'
            },
            screens: {
                xs: { max: '575px' },
                sm: { min: '576px' },
                md: { min: '992px' },
                lg: { min: '1200px' },
                xl: { min: '1600px' },
                xxl: { min: '1920px' }
            }
        }
    },
    variants: {
        extend: {},
        scrollbar: ['rounded']
    },
    // plugins: [
    //     require('@tailwindcss/line-clamp'),
    //     require('tailwind-scrollbar')({ nocompatible: true })
    // ]
}
