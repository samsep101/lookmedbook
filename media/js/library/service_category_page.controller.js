var ServiceCategoryPageController = function () {
  this.init = function () {
    $('.service-category__description ul').addClass('list list-description');
    $('.service-category__description ul li ul').removeClass('list-description').addClass('list-description-2');
  };
};