{{--
  Template Name: Product Reviews Section
--}}

@php
  global $product;

  if (!comments_open()) {
    return;
  }

  $product_id = $product->get_id();

  // Properly setup comments for this product
  wp_reset_query();

  $comments = get_comments([
    'post_id' => $product_id,
    'status' => 'approve',
    'type' => 'review'
  ]);

  $comment_count = count($comments);
@endphp

<div id="reviews" class="woocommerce-Reviews container">
  <div id="comments">
    <h2 class="woocommerce-Reviews-title">
      @if ($comment_count > 0)
        {{ $comment_count }} {{ _n('отзив', 'отзива', $comment_count) }}
      @else
        Отзиви
      @endif
    </h2>

    @if ($comment_count > 0)
      <ol class="commentlist">
        @foreach ($comments as $comment)
          @php
            $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
            $review_images = get_comment_meta($comment->comment_ID, 'review_images', true);
            $review_images = $review_images ? (is_array($review_images) ? $review_images : []) : [];
          @endphp
          <li class="review comment byuser comment-author-{{ $comment->user_id }} bypostauthor even thread-even depth-1" id="li-comment-{{ $comment->comment_ID }}">
            <div id="comment-{{ $comment->comment_ID }}" class="comment_container">
              {!! get_avatar($comment, 60) !!}
              <div class="comment-text">
                @if ($rating && wc_review_ratings_enabled())
                  <div class="star-rating" role="img" aria-label="Оценено с {{ $rating }} от 5">
                    <span style="width:{{ ($rating / 5) * 100 }}%">
                      Оценено с <strong class="rating">{{ $rating }}</strong> от 5
                    </span>
                  </div>
                @endif
                <p class="meta">
                  <strong class="woocommerce-review__author">{{ $comment->comment_author }}</strong>
                  <span class="woocommerce-review__dash">–</span>
                  <time class="woocommerce-review__published-date" datetime="{{ get_comment_date('c', $comment->comment_ID) }}">
                    {{ get_comment_date('F j, Y', $comment->comment_ID) }}
                  </time>
                </p>
                <div class="description">
                  <p>{{ $comment->comment_content }}</p>
                </div>
                @if (!empty($review_images))
                  <div class="review-images">
                    @foreach ($review_images as $image_id)
                      @php
                        $image_url = wp_get_attachment_image_url($image_id, 'medium');
                        $full_image_url = wp_get_attachment_image_url($image_id, 'full');
                      @endphp
                      @if ($image_url)
                        <a href="{{ $full_image_url }}" class="review-image-link" data-lightbox="review-{{ $comment->comment_ID }}">
                          <img src="{{ $image_url }}" alt="Review image" loading="lazy" />
                        </a>
                      @endif
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          </li>
        @endforeach
      </ol>

      @if (get_comment_pages_count() > 1 && get_option('page_comments'))
        <nav class="woocommerce-pagination">
          @php
            paginate_comments_links(apply_filters('woocommerce_comment_pagination_args', [
              'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
              'next_text' => is_rtl() ? '&larr;' : '&rarr;',
              'type'      => 'list',
            ]));
          @endphp
        </nav>
      @endif
    @else
      <p class="woocommerce-noreviews">Все още няма отзиви.</p>
    @endif
  </div>

  @if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product_id))
    <div id="review_form_wrapper">
      <div id="review_form">
        <div id="respond" class="comment-respond">
          <h3 id="reply-title" class="comment-reply-title">
            @if ($comment_count > 0)
              Добавяне на отзив
            @else
              Бъдете първи да напишете отзив за "{{ get_the_title() }}"
            @endif
          </h3>

          <form action="{{ esc_url(get_option('siteurl')) }}/wp-comments-post.php" method="post" id="commentform" class="comment-form" enctype="multipart/form-data">
            @if (wc_review_ratings_enabled())
              <div class="comment-form-rating">
                <label for="rating">Вашата оценка&nbsp;<span class="required">*</span></label>
                <div class="stars-rating-input">
                  <input type="hidden" name="rating" id="rating" value="" required />
                  <div class="stars-wrapper">
                    <span class="star" data-rating="1">★</span>
                    <span class="star" data-rating="2">★</span>
                    <span class="star" data-rating="3">★</span>
                    <span class="star" data-rating="4">★</span>
                    <span class="star" data-rating="5">★</span>
                  </div>
                  <span class="rating-text"></span>
                </div>
              </div>
            @endif

            <p class="comment-form-comment">
              <label for="comment">Вашият отзив&nbsp;<span class="required">*</span></label>
              <textarea id="comment" name="comment" cols="45" rows="8" required></textarea>
            </p>

            <div class="comment-form-images">
              <label for="review_images">Снимки (до 5 снимки)</label>
              <input type="file" name="review_images[]" id="review_images" accept="image/jpeg,image/jpg,image/png,image/webp" multiple />
              <p class="description">Максимална големина на файл: 5MB. Позволени формати: JPG, PNG, WebP</p>
              <div id="image-preview" class="image-preview"></div>
            </div>

            @php
              $commenter = wp_get_current_commenter();
              $req = get_option('require_name_email');
            @endphp

            <p class="comment-form-author">
              <label for="author">Име&nbsp;@if($req)<span class="required">*</span>@endif</label>
              <input id="author" name="author" type="text" value="{{ esc_attr($commenter['comment_author']) }}" size="30" {{ $req ? 'required' : '' }} />
            </p>

            <p class="comment-form-email">
              <label for="email">Email&nbsp;@if($req)<span class="required">*</span>@endif</label>
              <input id="email" name="email" type="email" value="{{ esc_attr($commenter['comment_author_email']) }}" size="30" {{ $req ? 'required' : '' }} />
            </p>

            <p class="form-submit">
              <input name="submit" type="submit" id="submit" class="submit" value="Изпращане" />
              <input type="hidden" name="comment_post_ID" value="{{ $product_id }}" id="comment_post_ID" />
              <input type="hidden" name="comment_parent" id="comment_parent" value="0" />
            </p>
          </form>
        </div>
      </div>
    </div>
  @else
    <p class="woocommerce-verification-required">Само влезли клиенти, които са закупили този продукт, могат да оставят отзив.</p>
  @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const starsWrapper = document.querySelector('.stars-wrapper');
  if (!starsWrapper) return;

  const stars = starsWrapper.querySelectorAll('.star');
  const ratingInput = document.getElementById('rating');
  const ratingText = document.querySelector('.rating-text');
  const form = document.getElementById('commentform');

  const ratingLabels = {
    1: 'Много лошо',
    2: 'Лошо',
    3: 'Средно',
    4: 'Добро',
    5: 'Отлично'
  };

  // Handle star click
  stars.forEach(star => {
    star.addEventListener('click', function(e) {
      e.preventDefault();
      const rating = this.getAttribute('data-rating');
      ratingInput.value = rating;
      updateStars(rating);
      ratingText.textContent = ratingLabels[rating];
    });

    // Handle hover
    star.addEventListener('mouseenter', function() {
      const rating = this.getAttribute('data-rating');
      hoverStars(rating);
    });
  });

  // Reset hover effect when leaving stars wrapper
  starsWrapper.addEventListener('mouseleave', function() {
    const currentRating = ratingInput.value;
    if (currentRating) {
      updateStars(currentRating);
    } else {
      clearStars();
    }
  });

  // Validate rating before submit
  if (form) {
    form.addEventListener('submit', function(e) {
      if (ratingInput && !ratingInput.value) {
        e.preventDefault();
        alert('Моля, изберете оценка');
        starsWrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
      }
    });
  }

  function updateStars(rating) {
    stars.forEach((star, index) => {
      if (index < rating) {
        star.classList.add('selected');
        star.classList.remove('hover');
      } else {
        star.classList.remove('selected', 'hover');
      }
    });
  }

  function hoverStars(rating) {
    stars.forEach((star, index) => {
      if (index < rating) {
        star.classList.add('hover');
      } else {
        star.classList.remove('hover');
      }
    });
  }

  function clearStars() {
    stars.forEach(star => {
      star.classList.remove('selected', 'hover');
    });
  }

  // Handle image upload preview
  const imageInput = document.getElementById('review_images');
  const imagePreview = document.getElementById('image-preview');
  const maxFiles = 5;
  const maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

  if (imageInput && imagePreview) {
    imageInput.addEventListener('change', function(e) {
      imagePreview.innerHTML = '';
      const files = Array.from(e.target.files);

      if (files.length > maxFiles) {
        alert(`Можете да качите максимум ${maxFiles} снимки`);
        imageInput.value = '';
        return;
      }

      files.forEach((file, index) => {
        if (file.size > maxFileSize) {
          alert(`Файлът ${file.name} е твърде голям. Максималната големина е 5MB`);
          return;
        }

        if (!file.type.match('image.*')) {
          alert(`Файлът ${file.name} не е валиден формат на изображение`);
          return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
          const previewItem = document.createElement('div');
          previewItem.className = 'preview-item';
          previewItem.innerHTML = `
            <img src="${event.target.result}" alt="Preview ${index + 1}" />
            <button type="button" class="remove-image" data-index="${index}">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </button>
          `;
          imagePreview.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
      });
    });

    // Handle remove image from preview
    imagePreview.addEventListener('click', function(e) {
      if (e.target.closest('.remove-image')) {
        const btn = e.target.closest('.remove-image');
        const index = parseInt(btn.getAttribute('data-index'));

        // Remove from file input
        const dt = new DataTransfer();
        const files = Array.from(imageInput.files);
        files.forEach((file, i) => {
          if (i !== index) {
            dt.items.add(file);
          }
        });
        imageInput.files = dt.files;

        // Trigger change event to refresh preview
        imageInput.dispatchEvent(new Event('change'));
      }
    });
  }

  // Lightbox functionality for review images
  const reviewImageLinks = document.querySelectorAll('.review-image-link');
  reviewImageLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const fullImageUrl = this.getAttribute('href');

      // Create lightbox
      const lightbox = document.createElement('div');
      lightbox.className = 'review-lightbox';
      lightbox.innerHTML = `
        <div class="lightbox-overlay"></div>
        <div class="lightbox-content">
          <img src="${fullImageUrl}" alt="Review image" />
          <button class="lightbox-close">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
        </div>
      `;

      document.body.appendChild(lightbox);
      document.body.style.overflow = 'hidden';

      // Close lightbox
      const closeLightbox = () => {
        lightbox.remove();
        document.body.style.overflow = '';
      };

      lightbox.querySelector('.lightbox-close').addEventListener('click', closeLightbox);
      lightbox.querySelector('.lightbox-overlay').addEventListener('click', closeLightbox);

      // Close on ESC key
      const escHandler = (e) => {
        if (e.key === 'Escape') {
          closeLightbox();
          document.removeEventListener('keydown', escHandler);
        }
      };
      document.addEventListener('keydown', escHandler);
    });
  });
});
</script>
