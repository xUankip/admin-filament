<x-filament::widget>
    <x-filament::section class="h-[720px]">
        <x-slot name="heading">
            {{ $this->getHeading() }}
        </x-slot>


        <div class="h-[670px] w-full p-4 bg-white dark:bg-gray-900 rounded-lg">
            <canvas
                x-data="{
                    chart: null,
                    isDark: window.matchMedia('(prefers-color-scheme: dark)').matches || document.documentElement.classList.contains('dark'),

                    init() {
                        // Load Chart.js nếu chưa có
                        if (typeof Chart === 'undefined') {
                            this.loadChartJS();
                            return;
                        }

                        this.createChart();

                        // Listen for theme changes
                        this.watchThemeChanges();
                    },

                    loadChartJS() {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                        script.onload = () => {
                            this.createChart();
                        };
                        document.head.appendChild(script);
                    },

                    createChart() {
                        this.$nextTick(() => {
                            const ctx = this.$el.getContext('2d');

                            // Get base config
                            const config = @js($this->getChartConfiguration());

                            // Apply theme-specific styling
                            this.applyTheme(config);

                            // Create chart
                            this.chart = new Chart(ctx, config);
                        });
                    },

                    applyTheme(config) {
                        const textColor = this.isDark ? '#e5e7eb' : '#000000'; // Explicitly set light theme to black
                        const gridColor = this.isDark ? '#374151' : '#000000'; // Explicitly set light theme grid to black
                        const backgroundColor = this.isDark ? '#1f2937' : '#ffffff';

                        // Update chart options for theme
                        config.options = {
                            ...config.options,
                            maintainAspectRatio: false,
                            responsive: true,
                            plugins: {
                                ...config.options.plugins,
                                legend: {
                                    ...config.options.plugins.legend,
                                    labels: {
                                        color: textColor, // Ensure legend text is black in light theme
                                        font: {
                                            family: 'Inter, sans-serif',
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    ...config.options.plugins.tooltip,
                                    enabled: true,
                                    backgroundColor: this.isDark ? '#374151' : '#ffffff',
                                    titleColor: textColor,
                                    bodyColor: textColor,
                                    borderColor: gridColor,
                                    borderWidth: 1
                                }
                            },
                            scales: {
                                x: {
                                    ...config.options.scales.x,
                                    ticks: {
                                        color: textColor, // Ensure x-axis dates are black in light theme
                                        font: {
                                            family: 'Inter, sans-serif',
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: gridColor,
                                        display: false
                                    }
                                },
                                y: {
                                    ...config.options.scales.y,
                                    ticks: {
                                        ...config.options.scales.y.ticks,
                                        color: textColor,
                                        font: {
                                            family: 'Inter, sans-serif',
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: gridColor,
                                        display: true
                                    }
                                }
                            }
                        };

                        // Update dataset colors for dark mode
                        if (config.data.datasets) {
                            config.data.datasets.forEach(dataset => {
                                if (this.isDark) {
                                    // Darker theme colors
                                    dataset.borderColor = '#10b981'; // emerald-500
                                    dataset.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                                } else {
                                    // Light theme colors
                                    dataset.borderColor = '#22c55e'; // green-500
                                    dataset.backgroundColor = 'rgba(34, 197, 94, 0.2)';
                                }
                            });
                        }
                    },

                    watchThemeChanges() {
                        // Watch for manual theme toggle
                        const observer = new MutationObserver(() => {
                            const newIsDark = document.documentElement.classList.contains('dark');
                            if (newIsDark !== this.isDark) {
                                this.isDark = newIsDark;
                                this.updateChartTheme();
                            }
                        });

                        observer.observe(document.documentElement, {
                            attributes: true,
                            attributeFilter: ['class']
                        });

                        // Watch for system theme changes
                        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                            if (!document.documentElement.classList.contains('dark') && !document.documentElement.classList.contains('light')) {
                                this.isDark = e.matches;
                                this.updateChartTheme();
                            }
                        });
                    },

                    updateChartTheme() {
                        if (!this.chart) return;

                        const textColor = this.isDark ? '#e5e7eb' : '#000000'; // Explicitly set light theme to black
                        const gridColor = this.isDark ? '#374151' : '#000000'; // Explicitly set light theme grid to black

                        // Update chart theme
                        this.chart.options.plugins.legend.labels.color = textColor; // Ensure legend text updates to black
                        this.chart.options.plugins.tooltip.backgroundColor = this.isDark ? '#374151' : '#ffffff';
                        this.chart.options.plugins.tooltip.titleColor = textColor;
                        this.chart.options.plugins.tooltip.bodyColor = textColor;
                        this.chart.options.plugins.tooltip.borderColor = gridColor;

                        this.chart.options.scales.x.ticks.color = textColor; // Ensure x-axis dates update to black
                        this.chart.options.scales.x.grid.color = gridColor;
                        this.chart.options.scales.y.ticks.color = textColor;
                        this.chart.options.scales.y.grid.color = gridColor;

                        // Update dataset colors
                        this.chart.data.datasets.forEach(dataset => {
                            if (this.isDark) {
                                dataset.borderColor = '#10b981';
                                dataset.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                            } else {
                                dataset.borderColor = '#22c55e';
                                dataset.backgroundColor = 'rgba(34, 197, 94, 0.2)';
                            }
                        });

                        this.chart.update();
                    },

                    destroy() {
                        if (this.chart) {
                            this.chart.destroy();
                            this.chart = null;
                        }
                    }
                }"
                x-on:livewire:update="
                    if (chart) {
                        chart.data = @js($this->getData());
                        chart.update('none');
                    }
                "
                wire:ignore
                class="w-full h-full"
                style="height: 250px !important;"
                width="800"
                height="222"
            ></canvas>
        </div>
    </x-filament::section>

</x-filament::widget>

