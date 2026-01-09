/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/View/Components/**/*.php",
        "./storage/framework/views/*.php",
    ],
    
    darkMode: 'class', // Enable dark mode support
    
    theme: {
        extend: {
            // Custom Colors for University Hostel Management
            colors: {
                // Primary University Colors
                'light-blue': '#87CEEB',
                'light-orange': '#FFB347',
                'primary-blue': '#4A90E2',
                'primary-orange': '#FF8C42',
                
                // Extended University Palette
                'university': {
                    50: '#f0f8ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#4A90E2', // Primary blue
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                
                'hostel': {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#FF8C42', // Primary orange
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                    950: '#431407',
                },
                
                // Status Colors
                'success': {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                },
                
                'warning': {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                
                'danger': {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
                
                // Gender-specific colors
                'boys': {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                
                'girls': {
                    50: '#fdf2f8',
                    100: '#fce7f3',
                    200: '#fbcfe8',
                    300: '#f9a8d4',
                    400: '#f472b6',
                    500: '#ec4899',
                    600: '#db2777',
                    700: '#be185d',
                    800: '#9d174d',
                    900: '#831843',
                },
            },
            
            // Typography
            fontFamily: {
                'sans': ['Inter', 'Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                'display': ['Inter', 'system-ui', 'sans-serif'],
                'body': ['Inter', 'system-ui', 'sans-serif'],
            },
            
            // Font Sizes
            fontSize: {
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.75rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                '5xl': ['3rem', { lineHeight: '1' }],
                '6xl': ['3.75rem', { lineHeight: '1' }],
                '7xl': ['4.5rem', { lineHeight: '1' }],
                '8xl': ['6rem', { lineHeight: '1' }],
                '9xl': ['8rem', { lineHeight: '1' }],
            },
            
            // Spacing
            spacing: {
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
                '128': '32rem',
            },
            
            // Border Radius
            borderRadius: {
                'none': '0',
                'sm': '0.125rem',
                'DEFAULT': '0.25rem',
                'md': '0.375rem',
                'lg': '0.5rem',
                'xl': '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
                'full': '9999px',
            },
            
            // Box Shadow
            boxShadow: {
                'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'sm': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                '2xl': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
                'inner': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
                'university': '0 10px 30px -5px rgba(74, 144, 226, 0.2)',
                'hostel': '0 10px 30px -5px rgba(255, 140, 66, 0.2)',
            },
            
            // Background Images
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                'university-gradient': 'linear-gradient(135deg, #4A90E2 0%, #87CEEB 100%)',
                'hostel-gradient': 'linear-gradient(135deg, #FF8C42 0%, #FFB347 100%)',
                'hero-pattern': "url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%239C92AC\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"4\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')",
            },
            
            // Animations
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'fade-in-up': 'fadeInUp 0.6s ease-out',
                'fade-in-down': 'fadeInDown 0.6s ease-out',
                'slide-in-left': 'slideInLeft 0.5s ease-out',
                'slide-in-right': 'slideInRight 0.5s ease-out',
                'bounce-slow': 'bounce 2s infinite',
                'pulse-slow': 'pulse 3s infinite',
                'float': 'float 3s ease-in-out infinite',
                'glow': 'glow 2s ease-in-out infinite alternate',
            },
            
            // Keyframes
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                glow: {
                    '0%': { boxShadow: '0 0 5px rgba(74, 144, 226, 0.5)' },
                    '100%': { boxShadow: '0 0 20px rgba(74, 144, 226, 0.8)' },
                },
            },
            
            // Screen Breakpoints
            screens: {
                'xs': '475px',
                'sm': '640px',
                'md': '768px',
                'lg': '1024px',
                'xl': '1280px',
                '2xl': '1536px',
                '3xl': '1920px',
            },
            
            // Z-Index
            zIndex: {
                '60': '60',
                '70': '70',
                '80': '80',
                '90': '90',
                '100': '100',
            },
            
            // Backdrop Blur
            backdropBlur: {
                'xs': '2px',
                'sm': '4px',
                'md': '8px',
                'lg': '12px',
                'xl': '16px',
                '2xl': '24px',
                '3xl': '40px',
            },
            
            // Container
            container: {
                center: true,
                padding: {
                    DEFAULT: '1rem',
                    sm: '2rem',
                    lg: '4rem',
                    xl: '5rem',
                    '2xl': '6rem',
                },
            },
        },
    },
    
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class', // Use class-based form styling
        }),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        
        // Custom Plugin for University Components
        function({ addComponents, theme }) {
            addComponents({
                // Button Components
                '.btn': {
                    padding: `${theme('spacing.2')} ${theme('spacing.4')}`,
                    borderRadius: theme('borderRadius.lg'),
                    fontWeight: theme('fontWeight.medium'),
                    display: 'inline-flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    transition: 'all 0.2s ease-in-out',
                    cursor: 'pointer',
                    '&:focus': {
                        outline: 'none',
                        boxShadow: `0 0 0 3px ${theme('colors.university.200')}`,
                    },
                },
                '.btn-primary': {
                    backgroundColor: theme('colors.primary-blue'),
                    color: theme('colors.white'),
                    '&:hover': {
                        backgroundColor: theme('colors.university.600'),
                        transform: 'translateY(-1px)',
                    },
                },
                '.btn-secondary': {
                    backgroundColor: theme('colors.primary-orange'),
                    color: theme('colors.white'),
                    '&:hover': {
                        backgroundColor: theme('colors.hostel.600'),
                        transform: 'translateY(-1px)',
                    },
                },
                '.btn-outline': {
                    backgroundColor: 'transparent',
                    borderWidth: '2px',
                    borderColor: theme('colors.primary-blue'),
                    color: theme('colors.primary-blue'),
                    '&:hover': {
                        backgroundColor: theme('colors.primary-blue'),
                        color: theme('colors.white'),
                    },
                },
                
                // Card Components
                '.card': {
                    backgroundColor: theme('colors.white'),
                    borderRadius: theme('borderRadius.xl'),
                    padding: theme('spacing.6'),
                    boxShadow: theme('boxShadow.sm'),
                    border: `1px solid ${theme('colors.gray.200')}`,
                },
                '.card-hover': {
                    transition: 'all 0.3s ease-in-out',
                    '&:hover': {
                        boxShadow: theme('boxShadow.university'),
                        transform: 'translateY(-2px)',
                    },
                },
                
                // Status Badges
                '.badge': {
                    padding: `${theme('spacing.1')} ${theme('spacing.3')}`,
                    borderRadius: theme('borderRadius.full'),
                    fontSize: theme('fontSize.xs'),
                    fontWeight: theme('fontWeight.medium'),
                    textTransform: 'uppercase',
                    letterSpacing: theme('letterSpacing.wide'),
                },
                '.badge-success': {
                    backgroundColor: theme('colors.success.100'),
                    color: theme('colors.success.800'),
                },
                '.badge-warning': {
                    backgroundColor: theme('colors.warning.100'),
                    color: theme('colors.warning.800'),
                },
                '.badge-danger': {
                    backgroundColor: theme('colors.danger.100'),
                    color: theme('colors.danger.800'),
                },
                '.badge-boys': {
                    backgroundColor: theme('colors.boys.100'),
                    color: theme('colors.boys.800'),
                },
                '.badge-girls': {
                    backgroundColor: theme('colors.girls.100'),
                    color: theme('colors.girls.800'),
                },
                
                // University Specific Components
                '.university-gradient': {
                    backgroundImage: theme('backgroundImage.university-gradient'),
                },
                '.hostel-gradient': {
                    backgroundImage: theme('backgroundImage.hostel-gradient'),
                },
                '.navbar-glass': {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    backdropFilter: 'blur(10px)',
                    borderBottom: `1px solid ${theme('colors.gray.200')}`,
                },
                '.sidebar-glass': {
                    backgroundColor: 'rgba(255, 255, 255, 0.98)',
                    backdropFilter: 'blur(15px)',
                },
            })
        },
    ],
}