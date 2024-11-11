{{-- 
  Template Name: Calculator 
--}}

@php
error_log('Calculator template is being used');
@endphp

@extends('layouts.app')

@section('content')
  @php
  if ( function_exists('yoast_breadcrumb') ) {
    yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
  }
  @endphp

  <header>
    <h1>Калкулатор</h1>
  </header>
  <section class="calculator-box">
    {{-- Calculator Tabs --}}
    <div class="calculator-nav">
      <button class="tab-button active " data-model="atlas">
        ATLAS Model
      </button>
      <button class="tab-button " data-model="gamma">
        GAMMA Model
      </button>
      <button class="tab-button " data-model="sigma">
        SIGMA Model
      </button>
      <button class="tab-button " data-model="piramida">
        PIRAMIDA Model
      </button>
      <button class="tab-button " data-model="terra">
        TERRA Model
      </button>
    </div>

    {{-- Atlas Calculator Tab --}}
    <div class="calculator-tab active" id="atlas-calculator">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - ATLAS </h2>
      
      <form id="atlas-fence-calculator" class="space-y-6">
        <div class="form-form">
          {{-- Panel Width Input --}}
          <div class="form-cell">
            <label for="atlas-panel-width" class="block text-14 font-medium text-label">
              Ширина на паното (m)
            </label>
            <input 
              type="number" 
              id="atlas-panel-width" 
              name="atlas-panel-width" 
              step="0.01" 
              min="0.3" 
              max="3.3" 
              required
             class="input-normal"
            >
          </div>

          {{-- Panel Height Select --}}
          <div class="form-cell">
            <label for="atlas-panel-height" class="block text-14 font-medium text-label">
              Височина на паното (m)
            </label>
            <select 
              id="atlas-panel-height" 
              name="atlas-panel-height" 
              required
              class="select-normal"
            >
              @foreach(range(0.745, 3.145, 0.1) as $height)
                <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
              @endforeach
            </select>
          </div>

          {{-- Number of Panels Input --}}
          <div class="form-cell">
            <label for="atlas-number-of-panels" class="block text-14 font-medium text-label">
              Брой пана (бр.)
            </label>
            <input 
              type="number" 
              id="atlas-number-of-panels" 
              name="atlas-number-of-panels" 
              min="1" 
              required
              class="input-normal"
            >
          </div>
        </div>
      </form>

      {{-- Results Section --}}
      <div class="mt-8 border border-border rounded-lg bg-white">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between">
          <h3 class="text-18 font-medium text-second">Какво включва комплекта:</h3>
          <button class="required-materials--toggle-link text-label hover:text-second focus:outline-none">
            <span class="toggle-icon text-18">+</span>
          </button>
        </div>
        
        <div id="atlas-calculator-results" class="hidden px-4 py-3">
          <div id="atlas-results" class="divide-y divide-border">
            {{-- Results will be populated by JavaScript --}}
          </div>

          <div id="atlas-final-price" class="mt-4 pt-4 border-t border-border font-medium text-second">
            {{-- Final price will be populated by JavaScript --}}
          </div>
        </div>
      </div>
    </div>

    {{-- Gamma Calculator Tab --}}
    <div class="calculator-tab" id="gamma-calculator" style="display: none;">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - GAMMA </h2>
      
      <form id="gamma-fence-calculator" class="space-y-6">
          <div class="form-row">
              <div class="form-cell">
                <label for="gamma-panel-width" class="block text-14 font-medium text-label">
                  Ширина на паното (m)
                </label>
                <input 
                  type="number" 
                  id="gamma-panel-width" 
                  name="gamma-panel-width" 
                  step="0.01" 
                  min="0.3" 
                  max="3.3" 
                  required
                  class="input-normal"
                >
              </div>
      
              <div class="form-cell">
                <label for="gamma-panel-height" class="block text-14 font-medium text-label">
                  Височина на паното (m)
                </label>
                <select 
                  id="gamma-panel-height" 
                  name="gamma-panel-height" 
                  required
                  class="select-normal"
                >
                  @foreach([0.85, 1.01, 1.17, 1.33, 1.49, 1.65, 1.81, 1.97, 2.13, 2.29, 2.45, 2.61, 2.77, 2.93, 3.09] as $height)
                    <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
                  @endforeach
                </select>
              </div>
      
              <div class="form-cell">
                <label for="gamma-number-of-panels" class="block text-14 font-medium text-label">
                  Брой пана (бр.)
                </label>
                <input 
                  type="number" 
                  id="gamma-number-of-panels" 
                  name="gamma-number-of-panels" 
                  min="1" 
                  required
                  class="input-normal"
                >
              </div>
          </div>
      </form>

      <div class="mt-8 border border-border rounded-lg bg-white">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between">
          <h3 class="text-18 font-medium text-second">Какво включва комплекта::</h3>
          <button class="required-materials--toggle-link text-label hover:text-second focus:outline-none">
            <span class="toggle-icon text-18">+</span>
          </button>
        </div>
        
        <div id="gamma-calculator-results" class="hidden px-4 py-3">
          <div id="gamma-results" class="divide-y divide-border">
            {{-- Results will be populated by JavaScript --}}
          </div>
        </div>
      </div>
    </div>

    {{-- Sigma Calculator Tab --}}
    <div class="calculator-tab" id="sigma-calculator" style="display: none;">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - SIGMA</h2>
      
      <form id="sigma-fence-calculator" class="space-y-6">
        <div class="row">
          <div class="form-cell">
            <label for="sigma-panel-width" class="block text-14 font-medium text-label">
              Ширина на паното (m)
            </label>
            <input 
              type="number" 
              id="sigma-panel-width" 
              name="sigma-panel-width" 
              step="0.01" 
              min="0.3" 
              max="3.3" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="sigma-panel-height" class="block text-14 font-medium text-label">
              Височина на паното (m)
            </label>
            <select 
              id="sigma-panel-height" 
              name="sigma-panel-height" 
              required
              class="select-normal"
            >
              @foreach(range(0.78, 3.1, 0.08) as $height)
                <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
              @endforeach
            </select>
          </div>
  
          <div class="form-cell">
            <label for="sigma-number-of-panels" class="block text-14 font-medium text-label">
              Брой пана (бр.)
            </label>
            <input 
              type="number" 
              id="sigma-number-of-panels" 
              name="sigma-number-of-panels" 
              min="1" 
              required
              class="input-normal"
            >
          </div>
        </div>
      </form>

      <div class="mt-8 border border-border rounded-lg bg-white">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between">
          <h3 class="text-18 font-medium text-second">Какво включва комплекта:</h3>
          <button class="required-materials--toggle-link text-label hover:text-second focus:outline-none">
            <span class="toggle-icon text-18">+</span>
          </button>
        </div>
        
        <div id="sigma-calculator-results" class="hidden px-4 py-3">
          <div id="sigma-results" class="divide-y divide-border">
            {{-- Results will be populated by JavaScript --}}
          </div>
        </div>
      </div>
    </div>

    {{-- Piramida Calculator Tab --}}
    <div class="calculator-tab" id="piramida-calculator" style="display: none;">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - PIRAMIDA</h2>
      
      <form id="piramida-fence-calculator" class="space-y-6">
          <div class="form-row">
            <div class="form-cell">
              <label for="piramida-panel-width" class="block text-14 font-medium text-label">
                Ширина на паното (m)
              </label>
              <input 
                type="number" 
                id="piramida-panel-width" 
                name="piramida-panel-width" 
                step="0.01" 
                min="0.3" 
                max="3.3" 
                required
                class="input-normal"
              >
            </div>
    
            <div class="form-cell">
              <label for="piramida-panel-height" class="block text-14 font-medium text-label">
                Височина на паното (m)
              </label>
              <select 
                id="piramida-panel-height" 
                name="piramida-panel-height" 
                required
                class="select-normal"
              >
                @foreach([0.775, 0.84, 0.905, 0.97, 1.035, 1.1, 1.165, 1.23, 1.295, 1.36, 1.425, 1.49, 1.555, 1.62, 1.685, 1.75, 1.815, 1.88, 1.945, 2.01, 2.075, 2.14, 2.205, 2.27, 2.335, 2.4, 2.465, 2.53, 2.595, 2.66, 2.725, 2.79, 2.855, 2.92, 2.985, 3.05] as $height)
                  <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
                @endforeach
              </select>
            </div>
    
            <div class="form-cell">
              <label for="piramida-number-of-panels" class="block text-14 font-medium text-label">
                Брой пана (бр.)
              </label>
              <input 
                type="number" 
                id="piramida-number-of-panels" 
                name="piramida-number-of-panels" 
                min="1" 
                required
                class="input-normal"
              >
            </div>
          </div>
      </form>

      <div class="mt-8 border border-border rounded-lg bg-white">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between">
          <h3 class="text-18 font-medium text-second">Какво включва комплекта:</h3>
          <button class="required-materials--toggle-link text-label hover:text-second focus:outline-none">
            <span class="toggle-icon text-18">+</span>
          </button>
        </div>
        
        <div id="piramida-calculator-results" class="hidden px-4 py-3">
          <div id="piramida-results" class="divide-y divide-border">
            {{-- Results will be populated by JavaScript --}}
          </div>
        </div>
      </div>
    </div>

    {{-- Terra Calculator Tab --}}
    <div class="calculator-tab" id="terra-calculator" style="display: none;">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - TERRA </h2>
      
      <form id="terra-fence-calculator" class="space-y-6">
        <div class="form-row">
          <div class="form-cell">
            <label for="terra-panel-width" class="block text-14 font-medium text-label">
              Ширина на паното (m)
            </label>
            <input 
              type="number" 
              id="terra-panel-width" 
              name="terra-panel-width" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-panel-height" class="block text-14 font-medium text-label">
              Височина на паното (m)
            </label>
            <input 
              type="number" 
              id="terra-panel-height" 
              name="terra-panel-height" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-panel-distance-cassettes" class="block text-14 font-medium text-label">
              Разстояние между ламели (cm)
            </label>
            <input 
              type="number" 
              id="terra-panel-distance-cassettes" 
              name="terra-panel-distance-cassettes" 
              required
              class="input-normal"
            >
          </div>
  
          
        </div>

        <div class="form-row">
          <div class="form-cell">
            <label for="terra-panel-base-distance" class="block text-14 font-medium text-label">
              Разстояние от основата (cm)
            </label>
            <input 
              type="number" 
              id="terra-panel-base-distance" 
              name="terra-panel-base-distance" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-number-of-panels" class="block text-14 font-medium text-label">
              Брой пана (бр.)
            </label>
            <input 
              type="number" 
              id="terra-number-of-panels" 
              name="terra-number-of-panels" 
              min="1" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-panel-optimal-height" class="block text-14 font-medium text-label">
              Препоръчителна височина (m)
            </label>
            <input 
              type="number" 
              id="terra-panel-optimal-height" 
              name="terra-panel-optimal-height" 
              readonly 
              disabled
              class="input-normal"
            >
          </div>
        </div>
      </form>

      <div class="mt-8 border border-border rounded-lg bg-white">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between">
          <h3 class="text-18 font-medium text-second">Какво включва комплекта:</h3>
          <button class="required-materials--toggle-link text-label hover:text-second focus:outline-none">
            <span class="toggle-icon text-18">+</span>
          </button>
        </div>
        
        <div id="terra-calculator-results" class="hidden px-4 py-3">
          <div id="terra-results" class="divide-y divide-border">
            {{-- Results will be populated by JavaScript --}}
          </div>
        </div>
      </div>
    </div>




  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    document.querySelectorAll('.tab-button').forEach(button => {
      button.addEventListener('click', () => {
        // Remove active classes
        document.querySelectorAll('.tab-button').forEach(btn => {
          btn.classList.remove('active', 'bg-main', 'text-white');
          btn.classList.add('bg-white', 'text-second');
        });
        
        // Add active classes
        button.classList.remove('bg-white', 'text-second');
        button.classList.add('active', 'bg-main', 'text-white');
        
        // Show corresponding calculator
        const model = button.dataset.model;
        document.querySelectorAll('.calculator-tab').forEach(tab => {
          tab.classList.toggle('active', tab.id === `${model}-calculator`);
          tab.style.display = tab.id === `${model}-calculator` ? 'block' : 'none';
        });
      });
    });
  });
</script>
@endpush