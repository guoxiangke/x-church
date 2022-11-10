const twColors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

const toRgba = hexCode => {
  let hex = hexCode.replace('#', '')

  if (hex.length === 3) {
    hex = `${hex[0]}${hex[0]}${hex[1]}${hex[1]}${hex[2]}${hex[2]}`
  }

  const r = parseInt(hex.substring(0, 2), 16)
  const g = parseInt(hex.substring(2, 4), 16)
  const b = parseInt(hex.substring(4, 6), 16)

  return `${r}, ${g}, ${b}`
}

const primaryColors = twColors.sky

function hexToRgb(hex) {
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)
  return result
    ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16),
      }
    : null
}

function generateRootCSSVars() {
  return Object.fromEntries(
    Object.keys(primaryColors).map(k => {
      return [`--colors-primary-${k}`, `${toRgba(primaryColors[k])}`]
    })
  )
}

function generateTailwindColors() {
  return Object.fromEntries(
    Object.keys(primaryColors).map(k => {
      return [`${k}`, `rgba(var(--colors-primary-${k}), <alpha-value>)`]
    })
  )
}

module.exports = {
  mode: 'jit',
  content: [
    './src/**/*.php',
    './src/**/*.vue',
    './resources/**/*{js,vue,blade.php}',
  ],
  darkMode: 'class', // or 'media' or 'class'
  // safelist: [
  // {
  // pattern: /^grid-cols-(?:\d)+/,
  // pattern: /grid-cols-(?:\d)+/,
  // pattern: /^(?:\w+:)?grid-cols-(?:\d)+/,
  // variants: ['sm', 'md', 'lg'],
  // },
  // ],

  theme: {
    extend: {
      colors: { primary: generateTailwindColors(), gray: twColors.slate },
      fontFamily: { sans: ['Nunito Sans', ...defaultTheme.fontFamily.sans] },
      fontSize: { xxs: '11px' },
      maxWidth: { xxs: '15rem' },
      minHeight: theme => theme('spacing'),
      minWidth: theme => theme('spacing'),

      spacing: {
        5: '1.25rem',
        9: '2.25rem',
        11: '2.75rem',
      },

      top: theme => theme('inset'),
      width: theme => theme('spacing'),
    },
  },
  plugins: [
    function ({ addBase }) {
      addBase({ ':root': generateRootCSSVars() })
    },
  ],
}
