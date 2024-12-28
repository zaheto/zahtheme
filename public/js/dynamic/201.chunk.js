"use strict";
(self["webpackChunk_roots_bud_sage_sage"] = self["webpackChunk_roots_bud_sage_sage"] || []).push([[201],{

/***/ "./scripts/calculator.js":
/*!*******************************!*\
  !*** ./scripts/calculator.js ***!
  \*******************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// File: calculator.js

jQuery(document).ready(function ($) {
  class Calculator {
    constructor() {
      console.log('Calculator initialized');
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
      $('.tab-button').on('click', e => {
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
        $(`#${model}-panel-width-calc`).on('input', e => {
          console.log(`Validating width for ${model}`);
          this.validateWidth(e, model);
        });
      });

      // Terra specific inputs
      $('#terra-panel-width-calc, #terra-panel-height-calc, #terra-panel-distance-cassettes-calc, #terra-panel-base-distance-calc, #terra-number-of-panels-calc').on('input change', () => {
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
      switch (model) {
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
    calculateStandardModel(model) {
      console.log('Calculating standard model:', model);
      const width = parseFloat($(`#${model}-panel-width-calc`).val()) || 0;
      const height = parseFloat($(`#${model}-panel-height-calc`).val()) || 0;
      const panels = parseInt($(`#${model}-number-of-panels-calc`).val()) || 0;
      console.log('Input values:', {
        width,
        height,
        panels
      });
      if (!width || !height || !panels) {
        console.log('Missing required values');
        return;
      }
      const modelConfig = this.models[model];
      const blindsProfilePcs = Math.max((height - modelConfig.blindsOffset) / modelConfig.blindsSpacing * panels, 0);
      const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);
      let F20 = this.calculateF20(width);
      const reinforcingProfilePcs = F20;
      const reinforcingProfileLm = height * F20 * panels;
      const rivetsPcs = this.calculateRivets(blindsProfilePcs, panels, F20);
      console.log('Calculated values:', {
        blindsProfilePcs,
        blindsProfileLm,
        F20,
        reinforcingProfilePcs,
        reinforcingProfileLm,
        rivetsPcs
      });
      const resultsContainer = $(`#${model}-results-calc`);
      const calculatorContainer = $(`#${model}-calculator-results-calc`);
      console.log(`Results container for ${model}:`, {
        exists: resultsContainer.length > 0,
        id: `#${model}-results-calc`
      });
      if (resultsContainer.length === 0) {
        console.error(`Results container for ${model} not found`);
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
      calculatorContainer.removeClass('hidden');
    }
    calculatePiramida() {
      const width = parseFloat($('#piramida-panel-width-calc').val()) || 0;
      const height = parseFloat($('#piramida-panel-height-calc').val()) || 0;
      const panels = parseInt($('#piramida-number-of-panels-calc').val()) || 0;
      console.log('Piramida input values:', {
        width,
        height,
        panels
      });
      if (!width || !height || !panels) {
        console.log('Missing required values for Piramida');
        return;
      }
      const blindsProfilePcs = Math.max((height - 0.06) / 0.065 * panels, 0);
      const blindsProfileLm = Math.max((width - 0.01) * blindsProfilePcs, 0);
      const rivetsPcs = Math.ceil((blindsProfilePcs + 1) * 4 / panels / 100) * 100 * panels;
      const resultsContainer = $('#piramida-results-calc');
      console.log('Piramida results container exists:', resultsContainer.length > 0);
      resultsContainer.html(`
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
      console.log('Terra input values:', {
        width,
        height,
        distanceCassettes,
        baseDistance,
        panels
      });
      if (!width || !height || !distanceCassettes || !baseDistance || !panels) {
        console.log('Missing required values for Terra');
        return;
      }
      const G15 = Math.floor((height - baseDistance / 100) / (0.108 + distanceCassettes / 100));
      const G16 = Math.ceil((height - baseDistance / 100) / (0.108 + distanceCassettes / 100));
      const H15 = G15 * 0.108 + (G15 - 1) * (distanceCassettes / 100) + baseDistance / 100;
      const H16 = G16 * 0.108 + (G16 - 1) * (distanceCassettes / 100) + baseDistance / 100;
      const G17 = Math.abs(height - H15);
      const H17 = Math.abs(height - H16);
      const optimalHeight = G17 <= H17 ? H15 : H16;
      console.log('Terra calculated values:', {
        G15,
        G16,
        H15,
        H16,
        G17,
        H17,
        optimalHeight
      });
      $('#terra-panel-optimal-height-calc').val(optimalHeight.toFixed(3));
      const profileCassettesPcs = (G17 <= H17 ? G15 : G16) * panels;
      const profileCassettesLm = Math.max((width - 0.01) * profileCassettesPcs, 0);
      const rivetsPcs = Math.round(profileCassettesPcs * 8) >= 101 ? panels * 200 : panels * 100;
      const resultsContainer = $('#terra-results-calc');
      console.log('Terra results container exists:', resultsContainer.length > 0);
      resultsContainer.html(`
                <p>Профил касети: ${profileCassettesPcs} Pcs, ${profileCassettesLm.toFixed(2)} lm</p>
                <p>Профил U: ${panels * 2} Pcs, ${(optimalHeight * panels * 2).toFixed(3)} lm</p>
                <p>Заклепки: ${rivetsPcs} Pcs</p>
                <p>Самонарезни винтове: ${panels * 10} Pcs</p>
                <p>Тапи: ${panels * 10} Pcs</p>
            `);
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
      return Math.ceil((innerCalculation + F20 * 2) / panels / 100) * 100 * panels;
    }
    validateWidth(event, model) {
      const value = parseFloat($(event.target).val());
      const {
        minWidth,
        maxWidth
      } = this.models[model];
      if (value < minWidth) $(event.target).val(minWidth);
      if (value > maxWidth) $(event.target).val(maxWidth);
    }
    setDefaultValues() {
      console.log('Setting default values');

      // Set defaults for standard models
      Object.keys(this.models).forEach(model => {
        console.log(`Setting defaults for ${model}`);
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
    console.log('Creating calculator instance');
    new Calculator();
  } else {
    console.log('No calculator tabs found, skipping initialization');
  }
});

/***/ })

}]);
//# sourceMappingURL=201.chunk.js.map