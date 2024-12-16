{{-- 
  Template Name: Calculator 
--}}

@php
error_log('Calculator template is being used');
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
@endphp

@extends('layouts.app')

@section('content')


  <header>
    <h1 class="text-h3 md:text-h2 font-bold mb-8 mt-6">{{ get_the_title() }}</h1>
    <div >{{ the_content() }}</div>
  </header>
  <section class="calculator-box">
    {{-- Calculator Tabs --}}
    <div class="calculator-nav">
      <button class="tab-button active " data-model="atlas">
        ATLAS
      </button>
      <button class="tab-button " data-model="gamma">
        GAMMA
      </button>
      <button class="tab-button " data-model="sigma">
        SIGMA
      </button>
      <button class="tab-button " data-model="piramida">
        PIRAMIDA
      </button>
      <button class="tab-button " data-model="terra">
        TERRA
      </button>
    </div>

    {{-- Atlas Calculator Tab --}}
    <section class="calculator-tab active" id="atlas-calculator">
      <div class="flex flex-col">
        <h2 class="text-24 font-bold mb-6">Калкулатор за модел - ATLAS </h2>
        <p>Въведете вашите индивидуални размери на оградата:</p>
        
        <form id="atlas-fence-calculator" class="space-y-6">
          <div class="form-row">
            {{-- Panel Width Input --}}
            <div class="form-cell">
              <label for="atlas-panel-width-calc" class="block text-14 font-medium text-label">
                Ширина на паното (m)
              </label>
              <input 
                type="number" 
                id="atlas-panel-width-calc" 
                name="atlas-panel-width-calc" 
                step="0.01" 
                min="0.3" 
                max="3.3" 
                required
              class="input-normal"
              >
            </div>

            {{-- Panel Height Select --}}
            <div class="form-cell">
              <label for="atlas-panel-height-calc" class="block text-14 font-medium text-label">
                Височина на паното (m)
              </label>
              <select 
                id="atlas-panel-height-calc" 
                name="atlas-panel-height-calc" 
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
              <label for="atlas-number-of-panels-calc" class="block text-14 font-medium text-label">
                Брой пана (бр.)
              </label>
              <input 
                type="number" 
                id="atlas-number-of-panels-calc" 
                name="atlas-number-of-panels-calc" 
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

          </div>
          
          <div id="atlas-calculator-results-calc" class="hidden px-4 py-3">
            <div id="atlas-results-calc" class="divide-y divide-border">
              {{-- Results will be populated by JavaScript --}}
            </div>

            <div id="atlas-final-price" class="mt-4 pt-4 border-t border-border font-medium text-second">
              {{-- Final price will be populated by JavaScript --}}
            </div>
          </div>
        </div>
      </div>
      @if($featured_img_url)
        <div class="featured-image ">
            <img src="{{ $featured_img_url }}" alt="{{ get_the_title() }}" class="w-full h-auto">
        </div>
      @endif

    </section>

    {{-- Gamma Calculator Tab --}}
    <section class="calculator-tab" id="gamma-calculator" style="display: none;">
      <div class="flex flex-col">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - GAMMA </h2>
      
      <form id="gamma-fence-calculator" class="space-y-6">
          <div class="form-row">
              <div class="form-cell">
                <label for="gamma-panel-width-calc" class="block text-14 font-medium text-label">
                  Ширина на паното (m)
                </label>
                <input 
                  type="number" 
                  id="gamma-panel-width-calc" 
                  name="gamma-panel-width-calc" 
                  step="0.01" 
                  min="0.3" 
                  max="3.3" 
                  required
                  class="input-normal"
                >
              </div>
      
              <div class="form-cell">
                <label for="gamma-panel-height-calc" class="block text-14 font-medium text-label">
                  Височина на паното (m)
                </label>
                <select 
                  id="gamma-panel-height-calc" 
                  name="gamma-panel-height-calc" 
                  required
                  class="select-normal"
                >
                  @foreach([0.85, 1.01, 1.17, 1.33, 1.49, 1.65, 1.81, 1.97, 2.13, 2.29, 2.45, 2.61, 2.77, 2.93, 3.09] as $height)
                    <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
                  @endforeach
                </select>
              </div>
      
              <div class="form-cell">
                <label for="gamma-number-of-panels-calc" class="block text-14 font-medium text-label">
                  Брой пана (бр.)
                </label>
                <input 
                  type="number" 
                  id="gamma-number-of-panels-calc" 
                  name="gamma-number-of-panels-calc" 
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

        </div>
        
        <div id="gamma-calculator-results-calc" class="hidden px-4 py-3">
          <div id="gamma-results-calc" class="divide-y divide-border">
              {{-- Results will be populated by JavaScript --}}
          </div>
      </div>
      </div>
    </div>
    @if($featured_img_url)
      <div class="featured-image ">
          <img src="{{ $featured_img_url }}" alt="{{ get_the_title() }}" class="w-full h-auto">
      </div>
    @endif

    </section>

    {{-- Sigma Calculator Tab --}}
    <section class="calculator-tab" id="sigma-calculator" style="display: none;">
      <div class="flex flex-col">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - SIGMA</h2>
      
      <form id="sigma-fence-calculator" class="space-y-6">
        <div class="form-row">
          <div class="form-cell">
            <label for="sigma-panel-width-calc" class="block text-14 font-medium text-label">
              Ширина на паното (m)
            </label>
            <input 
              type="number" 
              id="sigma-panel-width-calc" 
              name="sigma-panel-width-calc" 
              step="0.01" 
              min="0.3" 
              max="3.3" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="sigma-panel-height-calc" class="block text-14 font-medium text-label">
              Височина на паното (m)
            </label>
            <select 
              id="sigma-panel-height-calc" 
              name="sigma-panel-height-calc" 
              required
              class="select-normal"
            >
              @foreach(range(0.78, 3.1, 0.08) as $height)
                <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
              @endforeach
            </select>
          </div>
  
          <div class="form-cell">
            <label for="sigma-number-of-panels-calc" class="block text-14 font-medium text-label">
              Брой пана (бр.)
            </label>
            <input 
              type="number" 
              id="sigma-number-of-panels-calc" 
              name="sigma-number-of-panels-calc" 
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

        </div>
        
        <div id="sigma-calculator-results-calc" class="hidden px-4 py-3">
          <div id="sigma-results-calc" class="divide-y divide-border">
              {{-- Results will be populated by JavaScript --}}
          </div>
      </div>
      </div>
    </div>
    @if($featured_img_url)
      <div class="featured-image ">
          <img src="{{ $featured_img_url }}" alt="{{ get_the_title() }}" class="w-full h-auto">
      </div>
    @endif

    </section>

    {{-- Piramida Calculator Tab --}}
    <section class="calculator-tab" id="piramida-calculator" style="display: none;">
      <div class="flex flex-col">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - PIRAMIDA</h2>
      
      <form id="piramida-fence-calculator" class="space-y-6">
          <div class="form-row">
            <div class="form-cell">
              <label for="piramida-panel-width-calc" class="block text-14 font-medium text-label">
                Ширина на паното (m)
              </label>
              <input 
                type="number" 
                id="piramida-panel-width-calc" 
                name="piramida-panel-width-calc" 
                step="0.01" 
                min="0.3" 
                max="3.3" 
                required
                class="input-normal"
              >
            </div>
    
            <div class="form-cell">
              <label for="piramida-panel-height-calc" class="block text-14 font-medium text-label">
                Височина на паното (m)
              </label>
              <select 
                id="piramida-panel-height-calc" 
                name="piramida-panel-height-calc" 
                required
                class="select-normal"
              >
                @foreach([0.775, 0.84, 0.905, 0.97, 1.035, 1.1, 1.165, 1.23, 1.295, 1.36, 1.425, 1.49, 1.555, 1.62, 1.685, 1.75, 1.815, 1.88, 1.945, 2.01, 2.075, 2.14, 2.205, 2.27, 2.335, 2.4, 2.465, 2.53, 2.595, 2.66, 2.725, 2.79, 2.855, 2.92, 2.985, 3.05] as $height)
                  <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
                @endforeach
              </select>
            </div>
    
            <div class="form-cell">
              <label for="piramida-number-of-panels-calc" class="block text-14 font-medium text-label">
                Брой пана (бр.)
              </label>
              <input 
                type="number" 
                id="piramida-number-of-panels-calc" 
                name="piramida-number-of-panels-calc" 
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

        </div>
        
        <div id="piramida-calculator-results-calc" class="hidden px-4 py-3">
          <div id="piramida-results-calc" class="divide-y divide-border">
                {{-- Results will be populated by JavaScript --}}
            </div>
        </div>
      </div>
    </div>
    @if($featured_img_url)
      <div class="featured-image ">
          <img src="{{ $featured_img_url }}" alt="{{ get_the_title() }}" class="w-full h-auto">
      </div>
    @endif

    </section>

    {{-- Terra Calculator Tab --}}
    <section class="calculator-tab" id="terra-calculator" style="display: none;">
      <div class="flex flex-col">
      <h2 class="text-24 font-bold mb-6">Калкулатор за модел - TERRA </h2>
      
      <form id="terra-fence-calculator" class="space-y-6">
        <div class="form-row">
          <div class="form-cell">
            <label for="terra-panel-width-calc" class="block text-14 font-medium text-label">
              Ширина на паното (m)
            </label>
            <input 
              type="number" 
              id="terra-panel-width-calc" 
              name="terra-panel-width-calc" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-panel-height-calc" class="block text-14 font-medium text-label">
              Височина на паното (m)
            </label>
            <input 
              type="number" 
              id="terra-panel-height-calc" 
              name="terra-panel-height-calc" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-panel-distance-cassettes-calc" class="block text-14 font-medium text-label">
              Разстояние между ламели (cm)
            </label>
            <input 
              type="number" 
              id="terra-panel-distance-cassettes-calc" 
              name="terra-panel-distance-cassettes-calc" 
              required
              class="input-normal"
            >
          </div>
  
          
        </div>

        <div class="form-row">
          <div class="form-cell">
            <label for="terra-panel-base-distance-calc" class="block text-14 font-medium text-label">
              Разстояние от основата (cm)
            </label>
            <input 
              type="number" 
              id="terra-panel-base-distance-calc" 
              name="terra-panel-base-distance-calc" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-number-of-panels-calc" class="block text-14 font-medium text-label">
              Брой пана (бр.)
            </label>
            <input 
              type="number" 
              id="terra-number-of-panels-calc" 
              name="terra-number-of-panels-calc" 
              min="1" 
              required
              class="input-normal"
            >
          </div>
  
          <div class="form-cell">
            <label for="terra-panel-optimal-height-calc" class="block text-14 font-medium text-label">
              Препоръчителна височина (m)
            </label>
            <input 
              type="number" 
              id="terra-panel-optimal-height-calc" 
              name="terra-panel-optimal-height-calc" 
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
 
        </div>
        
        <div id="terra-calculator-results-calc" class="hidden px-4 py-3">
          <div id="terra-results-calc" class="divide-y divide-border">
                {{-- Results will be populated by JavaScript --}}
            </div>
        </div>
      </div>
    </div>
    @if($featured_img_url)
      <div class="featured-image ">
          <img src="{{ $featured_img_url }}" alt="{{ get_the_title() }}" class="w-full h-auto">
      </div>
    @endif

    </section>




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