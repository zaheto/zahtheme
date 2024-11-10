{{--
  Template Name: Product Specifications
--}}

@if(have_rows('product_specificatoins_add_product_specification'))
  <div class="product-specifications">
    <h3>Спецификации</h3>
    <table class="specifications-table">
      @while(have_rows('product_specificatoins_add_product_specification'))
        @php(the_row())
        <tr>
          <th>{!! esc_html(get_sub_field('column1')) !!}</th>
          <td>{!! wp_kses_post(get_sub_field('column2')) !!}</td>
        </tr>
      @endwhile
    </table>
  </div>
@endif