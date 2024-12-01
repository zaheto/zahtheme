jQuery(document).ready(function ($) {
    console.log('Calculator initialized');
    
    class Calculator {
        constructor() {
            this.models = {
                atlas: {
                    blindsOffset: 0.045,
                    blindsSpacing: 0.1,
                    minWidth: 0.3,
                    maxWidth: 3.3
                },
                gamma: {
                    blindsOffset: 0.05,
                    blindsSpacing: 0.16,
                    minWidth: 0.3,
                    maxWidth: 3.3
                },
                sigma: {
                    blindsOffset: 0.06,
                    blindsSpacing: 0.08,
                    minWidth: 0.3,
                    maxWidth: 3.3
                },
                piramida: {
                    blindsOffset: 0.06,
                    blindsSpacing: 0.065,
                    minWidth: 0.3,
                    maxWidth: 3.3
                }
            };

            this.init();
        }

        init() {
            if (!$('.calculator-tab').length) return;
            
            this.setupEventListeners();
            this.setupToggleButtons();
            this.setDefaultValues();
        }

        setupEventListeners() {
            // Tab switching
            $('.tab-button').on('click', (e) => {
                const model = $(e.target).data('model');
                this.switchTab(model);
            });

            // Setup input listeners for all models
            Object.keys(this.models).forEach(model => {
                // Calculate on any input change
                $(`#${model}-panel-width-calc, #${model}-panel-height-calc, #${model}-number-of-panels-calc`).on('input change', () => {
                    this.calculateModel(model);
                });

                // Width validation
                $(`#${model}-panel-width-calc`).on('input', (e) => {
                    this.validateWidth(e, model);
                });
            });

            // Terra specific inputs
            $('#terra-panel-width-calc, #terra-panel-height-calc, #terra-panel-distance-cassettes-calc, #terra-panel-base-distance-calc, #terra-number-of-panels-calc')
                .on('input change', () => this.calculateTerra());
        }

        // setupToggleButtons() {
        //     $('.required-materials--toggle-link').on('click', function(e) {
        //         e.preventDefault();
        //         const resultsDiv = $(this).closest('.mt-8').find('[id$="calculator-results"]');
        //         const toggleIcon = $(this).find('.toggle-icon');
        //         resultsDiv.slideToggle(300, function() {
        //             toggleIcon.text($(this).is(':visible') ? '-' : '+');
        //         });
        //     });
        // }

        switchTab(model) {
            console.log('Switching to model:', model);
            $('.tab-button').removeClass('active bg-main text-white').addClass('bg-white text-second');
            $(`.tab-button[data-model="${model}"]`).removeClass('bg-white text-second').addClass('active bg-main text-white');
            $('.calculator-tab').hide().removeClass('active');
            $(`#${model}-calculator`).show().addClass('active');
            this.calculateModel(model);
        }

        calculateModel(model) {
            console.log('Calculating model:', model);
            switch(model) {
                case 'atlas':
                    this.calculateAtlas();
                    break;
                case 'gamma':
                    this.calculateGamma();
                    break;
                case 'sigma':
                    this.calculateSigma();
                    break;
                case 'piramida':
                    this.calculatePiramida();
                    break;
                case 'terra':
                    this.calculateTerra();
                    break;
            }
        }

        calculateAtlas() {
            const width = parseFloat($('#atlas-panel-width-calc').val()) || 0;
            const height = parseFloat($('#atlas-panel-height-calc').val()) || 0;
            const panels = parseInt($('#atlas-number-of-panels-calc').val()) || 0;

            if (!width || !height || !panels) return;

            const blindsProfilePcs = Math.max((height - 0.045) / 0.1 * panels, 0);
            const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);

            let F20 = 0;
            if (width > 1.29 && width < 2.1) F20 = 1;
            else if (width > 2.09) F20 = 2;

            const reinforcingProfilePcs = F20;
            const reinforcingProfileLm = height * F20 * panels;
            const rivetsPcs = this.calculateRivets(blindsProfilePcs, panels, F20);

            $('#atlas-results-calc').html(`
                <p>Профил Жалюзи: ${blindsProfilePcs.toFixed(2)} Pcs, ${blindsProfileLm.toFixed(2)} lm</p>
                <p>Профил U отляво: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Профил U тодясно: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Хоризонтален профил U: ${panels} Pcs, ${(width * panels).toFixed(2)} lm</p>
                <p>Укрепващ профил: ${reinforcingProfilePcs} Pcs, ${reinforcingProfileLm.toFixed(3)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10 + F20 * panels} Pcs</p>
                <p>Ъгъл: ${F20 * panels} Pcs</p>
            `);

            $('#atlas-calculator-results-calc').removeClass('hidden');
        }

        calculateGamma() {
            const width = parseFloat($('#gamma-panel-width-calc').val()) || 0;
            const height = parseFloat($('#gamma-panel-height-calc').val()) || 0;
            const panels = parseInt($('#gamma-number-of-panels-calc').val()) || 0;

            if (!width || !height || !panels) return;

            const blindsProfilePcs = Math.max((height - 0.05) / 0.16 * panels, 0);
            const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);

            let F20 = 0;
            if (width > 1.29 && width < 2.1) F20 = 1;
            else if (width > 2.09) F20 = 2;

            const reinforcingProfilePcs = F20;
            const reinforcingProfileLm = height * F20 * panels;
            const rivetsPcs = this.calculateRivets(blindsProfilePcs, panels, F20);

            $('#gamma-results-calc').html(`
                <p>Профил Жалюзи: ${blindsProfilePcs.toFixed(2)} Pcs, ${blindsProfileLm.toFixed(2)} lm</p>
                <p>Профил U отляво: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Профил U тодясно: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Хоризонтален профил U: ${panels} Pcs, ${(width * panels).toFixed(2)} lm</p>
                <p>Укрепващ профил: ${reinforcingProfilePcs} Pcs, ${reinforcingProfileLm.toFixed(3)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10 + F20 * panels} Pcs</p>
                <p>Ъгъл: ${F20 * panels} Pcs</p>
            `);

            $('#gamma-calculator-results-calc').removeClass('hidden');
        }

        calculateSigma() {
            const width = parseFloat($('#sigma-panel-width-calc').val()) || 0;
            const height = parseFloat($('#sigma-panel-height-calc').val()) || 0;
            const panels = parseInt($('#sigma-number-of-panels-calc').val()) || 0;

            if (!width || !height || !panels) return;

            const blindsProfilePcs = Math.max((height - 0.06) / 0.08 * panels, 0);
            const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);

            let F20 = 0;
            if (width > 1.29 && width < 2.1) F20 = 1;
            else if (width > 2.09) F20 = 2;

            const reinforcingProfilePcs = F20;
            const reinforcingProfileLm = height * F20 * panels;
            const rivetsPcs = this.calculateRivets(blindsProfilePcs, panels, F20);

            $('#sigma-results-calc').html(`
                <p>Профил Жалюзи: ${blindsProfilePcs.toFixed(2)} Pcs, ${blindsProfileLm.toFixed(2)} lm</p>
                <p>Профил U отляво: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Профил U тодясно: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Хоризонтален профил U: ${panels} Pcs, ${(width * panels).toFixed(2)} lm</p>
                <p>Укрепващ профил: ${reinforcingProfilePcs} Pcs, ${reinforcingProfileLm.toFixed(3)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10 + F20 * panels} Pcs</p>
                <p>Ъгъл: ${F20 * panels} Pcs</p>
            `);

            $('#sigma-calculator-results-calc').removeClass('hidden');
        }

        calculatePiramida() {
            const width = parseFloat($('#piramida-panel-width-calc').val()) || 0;
            const height = parseFloat($('#piramida-panel-height-calc').val()) || 0;
            const panels = parseInt($('#piramida-number-of-panels-calc').val()) || 0;

            if (!width || !height || !panels) return;

            const blindsProfilePcs = Math.max((height - 0.06) / 0.065 * panels, 0);
            const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);

            let F20 = 0;
            if (width > 1.29 && width < 2.1) F20 = 1;
            else if (width > 2.09) F20 = 2;

            const rivetsPcs = Math.ceil(((blindsProfilePcs + 1) * 4) / panels / 100) * 100 * panels;

            $('#piramida-results-calc').html(`
                <p>Профил Жалюзи: ${blindsProfilePcs.toFixed(2)} Pcs, ${blindsProfileLm.toFixed(2)} lm</p>
                <p>Профил U отляво: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Профил U тодясно: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Хоризонтален профил U: ${panels} Pcs, ${(width * panels).toFixed(2)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10} Pcs</p>
            `);

            $('#piramida-calculator-results-calc').removeClass('hidden');
        }

        calculateTerra() {
            const width = parseFloat($('#terra-panel-width-calc').val()) || 0;
            const height = parseFloat($('#terra-panel-height-calc').val()) || 0;
            const distanceCassettes = parseFloat($('#terra-panel-distance-cassettes-calc').val()) || 0;
            const baseDistance = parseFloat($('#terra-panel-base-distance-calc').val()) || 0;
            const panels = parseInt($('#terra-number-of-panels-calc').val()) || 0;

            if (!width || !height || !distanceCassettes || !baseDistance || !panels) return;

            const G15 = Math.floor((height - baseDistance / 100) / (0.108 + distanceCassettes / 100));
            const G16 = Math.ceil((height - baseDistance / 100) / (0.108 + distanceCassettes / 100));
            const H15 = G15 * 0.108 + (G15 - 1) * (distanceCassettes / 100) + (baseDistance / 100);
            const H16 = G16 * 0.108 + (G16 - 1) * (distanceCassettes / 100) + (baseDistance / 100);
            const G17 = Math.abs(height - H15);
            const H17 = Math.abs(height - H16);
            const optimalHeight = G17 <= H17 ? H15 : H16;

            $('#terra-panel-optimal-height-calc').val(optimalHeight.toFixed(3));

            const profileCassettesPcs = (G17 <= H17 ? G15 : G16) * panels;
            const profileCassettesLm = Math.max((width - 0.01) * profileCassettesPcs, 0);
            const rivetsPcs = Math.round(profileCassettesPcs * 8) >= 101 ? panels * 200 : panels * 100;

            $('#terra-results-calc').html(`
                <p>Profile Cassettes: ${profileCassettesPcs} Pcs, ${profileCassettesLm.toFixed(2)} lm</p>
                <p>U Profile: ${panels * 2} Pcs, ${(optimalHeight * panels * 2).toFixed(3)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10} Pcs</p>
            `);

            $('#terra-calculator-results-calc').removeClass('hidden');
        }

        calculateRivets(blindsProfilePcs, panels, F20) {
            let innerCalculation = 0;
            if (F20 === 0) {
                innerCalculation = (blindsProfilePcs / panels + 1) * 4 * panels;
            } else if (F20 === 1) {
                innerCalculation = (blindsProfilePcs / panels + 1) * 5 * panels;
            } else if (F20 === 2) {
                innerCalculation = (blindsProfilePcs / panels + 1) * 6 * panels;
            }
            return Math.ceil((innerCalculation + (F20 * 2)) / panels / 100) * 100 * panels;
        }

        validateWidth(event, model) {
            const value = parseFloat($(event.target).val());
            const { minWidth, maxWidth } = this.models[model];
            
            if (value < minWidth) $(event.target).val(minWidth);
        }

        setDefaultValues() {
            console.log('Setting default values');
            // Set defaults for standard models
            Object.keys(this.models).forEach(model => {
                $(`#${model}-panel-width-calc`).val(1.8);
                $(`#${model}-panel-height-calc`).find('option:first').prop('selected', true);
                $(`#${model}-number-of-panels-calc`).val(1);

                // Trigger initial calculation
                this.calculateModel(model);
            });

            // Set defaults for Terra model
            $('#terra-panel-width-calc').val(1.8);
            $('#terra-panel-height-calc').val(2.0);
            $('#terra-panel-distance-cassettes-calc').val(10);
            $('#terra-panel-base-distance-calc').val(10);
            $('#terra-number-of-panels-calc').val(1);
            this.calculateTerra();
        }
    }

    // Initialize calculator when document is ready
    if ($('.calculator-tab').length) {
        console.log('Initializing calculator');
        new Calculator();
    } else {
        console.log('No calculator tabs found');
    }
});