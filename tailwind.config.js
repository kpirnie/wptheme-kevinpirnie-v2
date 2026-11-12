
module.exports = {
    theme: {
        extend: {
            colors: {
                'kp-light-blue': '#599bb8',
                'kp-lightish-blue': '#43819c',
                'kp-blue': '#2d7696',
                'kp-darkish-blue': '#1c375c',
                'kp-dark-blue': '#01123c',
                'kp-darkest-blue': '#000d2d',
                'kp-orange': '#fd6a4f',
                'kp-gray': '#cccccc',

                // Dark theme surfaces
                'kp-background': '#080f1e',
                'kp-surface': '#111d30',
                'kp-surface-raised': '#17283e',
                'kp-border': '#294158',

                // Typography
                'kp-text': '#f1f5f9',
                'kp-muted': '#a9b9ca',
            },
            fontFamily: {
                sans: [
                    'Inter',
                    'system-ui',
                    'sans-serif',
                ],
                mono: [
                    'Source Code Pro',
                    'monospace',
                ],
            },
            boxShadow: {
                'kp-card': '0 8px 24px rgba(0, 0, 0, 0.18)',
                'kp-header': '0 4px 20px rgba(0, 0, 0, 0.15)',
            },
        },
    },
};
