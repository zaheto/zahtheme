// File: calculator.js

jQuery(document).ready(function ($) {
    class Calculator {
        constructor() {
            console.log('Calculator initialized');
            console.log('Available Products:', {
                atlas: window.atlasProducts,
                gamma: window.gammaProducts,
                sigma: window.sigmaProducts,
                piramida: window.piramidaProducts,
                terra: window.terraProducts
            });
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
            console.log('Initializing calculator');
            if (!$('.calculator-tab').length) {
                console.log('No calculator tabs found');
                return;
            }
            
            this.setupEventListeners();
            this.setDefaultValues();
        }

        setupEventListeners() {
            console.log('Setting up event listeners');
            
            // Tab switching
            $('.tab-button').on('click', (e) => {
                const model = $(e.target).data('model');
                console.log('Tab clicked:', model);
                this.switchTab(model);
            });

            // Setup input event listeners for all models
            Object.keys(this.models).forEach(model => {
                console.log('Setting up listeners for model:', model);
                
                // Calculate on any input change
                $(`#${model}-panel-width-calc, #${model}-panel-height-calc, #${model}-number-of-panels-calc`).on('input change', () => {
                    console.log(`Input changed for ${model}`);
                    this.calculateModel(model);
                });

                // Width validation
                $(`#${model}-panel-width-calc`).on('input', (e) => {
                    console.log(`Validating width for ${model}`);
                    this.validateWidth(e, model);
                });
            });

            // Terra specific inputs
            $('#terra-panel-width-calc, #terra-panel-height-calc, #terra-panel-distance-cassettes-calc, #terra-panel-base-distance-calc, #terra-number-of-panels-calc')
                .on('input change', () => {
                    console.log('Terra inputs changed');
                    this.calculateTerra();
                });
        }

        switchTab(model) {
            console.log('Switching to tab:', model);
            $('.tab-button').removeClass('active bg-main text-white').addClass('bg-white text-second');
            $(`.tab-button[data-model="${model}"]`).removeClass('bg-white text-second').addClass('active bg-main text-white');
            $('.calculator-tab').hide().removeClass('active');
            $(`#${model}-calculator`).show().addClass('active');
            this.calculateModel(model);
        }

        calculateModel(model) {
            console.log('Calculating for model:', model);
            
            switch(model) {
                case 'atlas':
                    this.calculateStandardModel('atlas');
                    break;
                case 'gamma':
                    this.calculateStandardModel('gamma');
                    break;
                case 'sigma':
                    this.calculateStandardModel('sigma');
                    break;
                case 'piramida':
                    this.calculatePiramida();
                    break;
                case 'terra':
                    this.calculateTerra();
                    break;
            }
        }

        calculatePokritiePrices(calculatedData, model) {
            if (!window[`${model}Products`]) {
                return '';
            }
        
            // Group products by pokritie type
            const groupedProducts = window[`${model}Products`].reduce((acc, product) => {
                const pokritie = product.pokritie;
                if (!acc[pokritie]) {
                    acc[pokritie] = product;
                }
                return acc;
            }, {});
        
            return Object.values(groupedProducts).map(product => {
                const totalPrice = this.calculateTotalPrice(calculatedData, product);
                const salePrice = product.sale_price ? 
                    this.calculateTotalPrice(calculatedData, product, true) : null;
        
                return `
                    <div class="pokritie-boxes">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-base font-medium">${product.pokritie}</span>
                                <div class="prices-pokritie">
                                    ${salePrice ? 
                                        `<del class="text-gray-500">${totalPrice.toFixed(2)} лв.</del> 
                                         <span class="text-red-600 ml-2">${salePrice.toFixed(2)} лв.</span>` 
                                        : `<span>${totalPrice.toFixed(2)} лв.</span>`}
                                </div>
                            </div>
                            <a href="${product.link}" class="">
                                <svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect y="0.5" width="40" height="40" rx="5" fill="white"/>
                                    <path d="M23.5799 20.5C23.5799 22.48 21.9799 24.08 19.9999 24.08C18.0199 24.08 16.4199 22.48 16.4199 20.5C16.4199 18.52 18.0199 16.92 19.9999 16.92C21.9799 16.92 23.5799 18.52 23.5799 20.5Z" stroke="#0F4C81" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M20.0001 28.7699C23.5301 28.7699 26.8201 26.6899 29.1101 23.0899C30.0101 21.6799 30.0101 19.3099 29.1101 17.8999C26.8201 14.2999 23.5301 12.2199 20.0001 12.2199C16.4701 12.2199 13.1801 14.2999 10.8901 17.8999C9.99009 19.3099 9.99009 21.6799 10.8901 23.0899C13.1801 26.6899 16.4701 28.7699 20.0001 28.7699Z" stroke="#0F4C81" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        }

        calculateTotalPrice(calculatedData, product, useSalePrice = false) {
            console.log('Calculate Total Price inputs:', {
                calculatedData,
                product,
                useSalePrice
            });

            const basePrice = useSalePrice && product.sale_price ? 
                parseFloat(product.sale_price) : 
                parseFloat(product.price);
            
            console.log('Using base price:', basePrice);


            const total = calculatedData.blindsProfileLm * basePrice +
                calculatedData.uProfileLeftLm * product.component_prices.u_profile_left +
                calculatedData.uProfileRightLm * product.component_prices.u_profile_right +
                calculatedData.horizontalProfileLm * product.component_prices.u_horizontal_panel +
                calculatedData.reinforcingProfileLm * product.component_prices.reinforcing_profile +
                calculatedData.rivetsPcs * product.component_prices.rivets +
                calculatedData.selfTappingScrewPcs * product.component_prices.self_tapping_screw +
                calculatedData.dowelsPcs * product.component_prices.dowels +
                calculatedData.cornerPcs * product.component_prices.corners;
            
            console.log('Calculated total:', total);
            return total;
        }

        calculateStandardModel(model) {
            const width = parseFloat($(`#${model}-panel-width-calc`).val()) || 0;
            const height = parseFloat($(`#${model}-panel-height-calc`).val()) || 0;
            const panels = parseInt($(`#${model}-number-of-panels-calc`).val()) || 0;
        
            if (!width || !height || !panels) {
                return;
            }
        
            const modelConfig = this.models[model];
            const blindsProfilePcs = Math.max((height - modelConfig.blindsOffset) / modelConfig.blindsSpacing * panels, 0);
            const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);
        
            let F20 = this.calculateF20(width);
            const reinforcingProfilePcs = F20;
            const reinforcingProfileLm = height * F20 * panels;
            const rivetsPcs = this.calculateRivets(blindsProfilePcs, panels, F20);
        
            const calculatedData = {
                blindsProfileLm,
                uProfileLeftLm: height * panels,
                uProfileRightLm: height * panels,
                horizontalProfileLm: width * panels,
                reinforcingProfileLm,
                rivetsPcs,
                selfTappingScrewPcs: panels * 10,
                dowelsPcs: panels * 10 + F20 * panels,
                cornerPcs: F20 * panels
            };
        
            const resultsContainer = $(`#${model}-results-calc`);
            const calculatorContainer = $(`#${model}-calculator-results-calc`);
        
            if (resultsContainer.length === 0) {
                return;
            }
        
            resultsContainer.html(`
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
        
            const pokritieHtml = this.calculatePokritiePrices(calculatedData, model);
            if (pokritieHtml) {
                $(`#${model}-pokritie-prices`).html(pokritieHtml);
            }
        
            calculatorContainer.removeClass('hidden');
        }

        calculatePiramida() {
            const width = parseFloat($('#piramida-panel-width-calc').val()) || 0;
            const height = parseFloat($('#piramida-panel-height-calc').val()) || 0;
            const panels = parseInt($('#piramida-number-of-panels-calc').val()) || 0;
        
            if (!width || !height || !panels) {
                return;
            }
        
            const blindsProfilePcs = Math.max((height - 0.06) / 0.065 * panels, 0);
            const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);
            const rivetsPcs = Math.ceil(((blindsProfilePcs + 1) * 4) / panels / 100) * 100 * panels;
        
            const calculatedData = {
                blindsProfileLm,
                uProfileLeftLm: height * panels,
                uProfileRightLm: height * panels,
                horizontalProfileLm: width * panels,
                rivetsPcs,
                selfTappingScrewPcs: panels * 10,
                dowelsPcs: panels * 10,
                cornerPcs: 0,
                reinforcingProfileLm: 0
            };
        
            const resultsContainer = $('#piramida-results-calc');
        
            resultsContainer.html(`
                <p>Профил Жалюзи: ${blindsProfilePcs.toFixed(2)} Pcs, ${blindsProfileLm.toFixed(2)} lm</p>
                <p>Профил U отляво: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Профил U тодясно: ${panels} Pcs, ${(height * panels).toFixed(3)} lm</p>
                <p>Хоризонтален профил U: ${panels} Pcs, ${(width * panels).toFixed(2)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10} Pcs</p>
            `);
        
            const pokritieHtml = this.calculatePokritiePrices(calculatedData, 'piramida');
            if (pokritieHtml) {
                $('#piramida-pokritie-prices').html(pokritieHtml);
            }
        
            $('#piramida-calculator-results-calc').removeClass('hidden');
        }

        calculateTerra() {
            const width = parseFloat($('#terra-panel-width-calc').val()) || 0;
            const height = parseFloat($('#terra-panel-height-calc').val()) || 0;
            const distanceCassettes = parseFloat($('#terra-panel-distance-cassettes-calc').val()) || 0;
            const baseDistance = parseFloat($('#terra-panel-base-distance-calc').val()) || 0;
            const panels = parseInt($('#terra-number-of-panels-calc').val()) || 0;
        
            if (!width || !height || !distanceCassettes || !baseDistance || !panels) {
                return;
            }
        
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
        
            const calculatedData = {
                blindsProfileLm: profileCassettesLm,
                uProfileLeftLm: optimalHeight * panels,
                uProfileRightLm: optimalHeight * panels,
                horizontalProfileLm: 0,
                rivetsPcs,
                selfTappingScrewPcs: panels * 10,
                dowelsPcs: panels * 10,
                cornerPcs: 0,
                reinforcingProfileLm: 0
            };
        
            const resultsContainer = $('#terra-results-calc');
        
            resultsContainer.html(`
                <p>Профил касети: ${profileCassettesPcs} Pcs, ${profileCassettesLm.toFixed(2)} lm</p>
                <p>Профил U: ${panels * 2} Pcs, ${(optimalHeight * panels * 2).toFixed(3)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10} Pcs</p>
            `);
        
            const pokritieHtml = this.calculatePokritiePrices(calculatedData, 'terra');
            if (pokritieHtml) {
                $('#terra-pokritie-prices').html(pokritieHtml);
            }
        
            $('#terra-calculator-results-calc').removeClass('hidden');
        }

        calculateF20(width) {
            if (width > 2.09) return 2;
            if (width > 1.29) return 1;
            return 0;
        }

        calculateRivets(blindsProfilePcs, panels, F20) {
            const baseFactor = F20 === 0 ? 4 : F20 === 1 ? 5 : 6;
            const innerCalculation = (blindsProfilePcs / panels + 1) * baseFactor * panels;
            return Math.ceil((innerCalculation + (F20 * 2)) / panels / 100) * 100 * panels;
        }

        validateWidth(event, model) {
            const value = parseFloat($(event.target).val());
            const { minWidth, maxWidth } = this.models[model];
            
            if (value < minWidth) $(event.target).val(minWidth);
            if (value > maxWidth) $(event.target).val(maxWidth);
        }

        setDefaultValues() {
            //console.log('Setting default values');
            
            // Set defaults for standard models
            Object.keys(this.models).forEach(model => {
                //console.log(`Setting defaults for ${model}`);
                $(`#${model}-panel-width-calc`).val(1.8);
                $(`#${model}-panel-height-calc`).find('option:first').prop('selected', true);
                $(`#${model}-number-of-panels-calc`).val(1);
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

    // Initialize calculator
    if ($('.calculator-tab').length) {
        //console.log('Creating calculator instance');
        new Calculator();
    } else {
        console.log('No calculator tabs found, skipping initialization');
    }
});