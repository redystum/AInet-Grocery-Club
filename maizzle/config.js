export default {
  build: {
    tailwind: {
      css: 'src/css/tailwind.css',
      config: 'tailwind.config.js',
    },
    content: ['emails/**/*.blade.php', 'layouts/**/*.html'],
    output: {
      path: '../resources/views/emails/build',
      extension: 'php',
    },
    templates: {
      options: {
        autoescape: false,
      },
    }
  },
  css: {
    inline: true,
    purge: false,
    resolveCalc: true,
    resolveProps: true,
    safe: true,
    shorthand: true,
    sixHex: true,
  },
  prettify: true,
}
