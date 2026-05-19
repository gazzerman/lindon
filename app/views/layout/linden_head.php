<?php
declare(strict_types=1);

$page_title = $page_title ?? 'Linden — The vocabulary of legacy';
$page_description = $page_description ?? 'A discreet companion for the next generation of family stewards. Guided prompts for family meetings, inheritance, philanthropy, and legacy conversations.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>
    <meta name="description" content="<?= e($page_description) ?>">
    <meta name="author" content="Linden">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'ui-serif', 'Georgia', 'serif'],
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    letterSpacing: {
                        editorial: '0.2em',
                        display: '-0.02em',
                    },
                    colors: {
                        ivory: 'rgb(244 239 231 / <alpha-value>)',
                        background: 'rgb(244 239 231 / <alpha-value>)',
                        foreground: 'rgb(42 31 23 / <alpha-value>)',
                        card: {
                            DEFAULT: 'rgb(251 249 246 / <alpha-value>)',
                            foreground: 'rgb(42 31 23 / <alpha-value>)',
                        },
                        secondary: {
                            DEFAULT: 'rgb(235 230 222 / <alpha-value>)',
                            foreground: 'rgb(42 31 23 / <alpha-value>)',
                        },
                        muted: {
                            DEFAULT: 'rgb(230 224 214 / <alpha-value>)',
                            foreground: 'rgb(107 90 75 / <alpha-value>)',
                        },
                        accent: {
                            DEFAULT: 'rgb(168 121 74 / <alpha-value>)',
                            foreground: 'rgb(251 249 246 / <alpha-value>)',
                        },
                        border: 'rgb(219 210 198 / <alpha-value>)',
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-background text-foreground font-sans antialiased">
