{{-- 
  Template Name: Calculator 
--}}
@extends('layouts.app')

@section('content')
  <div class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="max-w-3xl mx-auto">
        {{-- Calculator Tabs --}}
        <div class="flex overflow-x-auto space-x-4 pb-4 mb-8">
          <button class="tab-button active flex-shrink-0 px-4 py-2 rounded-md border border-border transition-colors duration-200 focus:outline-none" data-model="atlas">
            ATLAS Model
          </button>
          <button class="tab-button flex-shrink-0 px-4 py-2 rounded-md border border-border transition-colors duration-200 focus:outline-none" data-model="gamma">
            GAMMA Model
          </button>
          <button class="tab-button flex-shrink-0 px-4 py-2 rounded-md border border-border transition-colors duration-200 focus:outline-none" data-model="sigma">
            SIGMA Model
          </button>
          <button class="tab-button flex-shrink-0 px-4 py-2 rounded-md border border-border transition-colors duration-200 focus:outline-none" data-model="piramida">
            PIRAMIDA Model
          </button>
          <button class="tab-button flex-shrink-0 px-4 py-2 rounded-md border border-border transition-colors duration-200 focus:outline-none" data-model="terra">
            TERRA Model
          </button>
        </div>

        {{-- Atlas Calculator Tab --}}
        <div class="calculator-tab active" id="atlas-calculator">
          <h2 class="text-24 font-bold mb-6">Fence Panel Calculator - ATLAS Model</h2>
          
          <form id="atlas-fence-calculator" class="space-y-6">
            {{-- Panel Width Input --}}
            <div class="space-y-2">
              <label for="atlas-panel-width" class="block text-14 font-medium text-label">
                Panel Width (m):
              </label>
              <input 
                type="number" 
                id="atlas-panel-width" 
                name="atlas-panel-width" 
                step="0.01" 
                min="0.3" 
                max="3.3" 
                required
                class="block w-full rounded-md border-border shadow-sm focus:border-main focus:ring-main text-14"
              >
            </div>

            {{-- Panel Height Select --}}
            <div class="space-y-2">
              <label for="atlas-panel-height" class="block text-14 font-medium text-label">
                Panel Height (m):
              </label>
              <select 
                id="atlas-panel-height" 
                name="atlas-panel-height" 
                required
                class="block w-full rounded-md border-border shadow-sm focus:border-main focus:ring-main text-14"
              >
                @foreach(range(0.745, 3.145, 0.1) as $height)
                  <option value="{{ number_format($height, 3) }}">{{ number_format($height, 3) }}</option>
                @endforeach
              </select>
            </div>

            {{-- Number of Panels Input --}}
            <div class="space-y-2">
              <label for="atlas-number-of-panels" class="block text-14 font-medium text-label">
                Number of Panels:
              </label>
              <input 
                type="number" 
                id="atlas-number-of-panels" 
                name="atlas-number-of-panels" 
                min="1" 
                required
                class="block w-full rounded-md border-border shadow-sm focus:border-main focus:ring-main text-14"
              >
            </div>

            {{-- Calculate Button --}}
            <button 
              type="button" 
              id="atlas-calculate"
              class="w-full bg-main text-white px-6 py-3 rounded-md font-medium hover:bg-button-hover transition-colors duration-200 text-14"
            >
              Calculate Materials
            </button>
          </form>

          {{-- Results Section --}}
          <div class="mt-8 border border-border rounded-lg bg-white">
            <div class="px-4 py-3 border-b border-border flex items-center justify-between">
              <h3 class="text-18 font-medium text-second">Required Materials:</h3>
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
      </div>
    </div>
  </div>
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