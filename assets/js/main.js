const Menu = () => {
  const menu_mobile = document.querySelector('.menu_burguer')
  const nav_menu = document.querySelectorAll('.menu')

  menu_mobile.addEventListener('click', () => {
    menu_mobile.classList.toggle('active')
    nav_menu.forEach((item) => {
      item.classList.toggle('active')
    })
  })
}

Menu()

document.addEventListener('DOMContentLoaded', function () {
  // Inicializa o carrossel de banners
  new Splide('#banner').mount();

  // Inicializa o carrossel de produtos
  
  new Splide('#product', {
    perPage: 3,
    type: 'loop',        
    trimSpace: false,    
    gap: '20px',         
    arrows: false,       
    pagination: false,    
    lazyLoad: 'nearby',
    breakpoints: {
      640: {
        perPage: 1,
      },
      1024: {
        perPage: 2,
      },
    },
  }).mount()
});