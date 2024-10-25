const Menu = () => {
  const menu_mobile = document.querySelector('.menu_burguer');
  const nav_menu = document.querySelectorAll('.menu');

  if (menu_mobile) { // Verificação para evitar erros caso o elemento não exista
    menu_mobile.addEventListener('click', () => {
      menu_mobile.classList.toggle('active');
      nav_menu.forEach((item) => {
        item.classList.toggle('active');
      });
    });
  }
}

Menu();

document.addEventListener('DOMContentLoaded', function () {
  // Inicializa o carrossel de banners se o elemento #banner existir
  const bannerElement = document.querySelector('#banner');
  if (bannerElement) {
    new Splide('#banner', ).mount();
  }

  // Inicializa o carrossel de produtos se o elemento #product existir
  const productElement = document.querySelector('#product');
  if (productElement) {
    new Splide('#product', {
      perPage: 3,
      type: 'loop',
      trimSpace: false,
      gap: '20px',
      arrows: true,
      pagination: true,
      lazyLoad: 'nearby',
      breakpoints: {
        640: {
          perPage: 1,
        },
        1024: {
          perPage: 2,
        },
      },
    }).mount();
  }

  // Inicializa o carrossel de comentários se o elemento #comentarios existir
  const comentariosElement = document.querySelector('#comentarios');
  if (comentariosElement) {
    console.log(comentariosElement);
    
    new Splide('#comentarios', {
      perPage: 2,
      gap: '20px',
      breakpoints: {
        640: {
          perPage:1,
        },
        990: {
          perPage: 2,
        },
      },
    }).mount();
  }

  
});

