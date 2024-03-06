if (document.querySelector('.ys-filter-price')) {
  const lowerSlider = document.querySelector('#ys-price-lower');
  const  upperSlider = document.querySelector('#ys-price-upper');
  
  document.querySelector('#ys-price-two').value=upperSlider.value;
  document.querySelector('#ys-price-one').value=lowerSlider.value;
  
  const  lowerVal = parseInt(lowerSlider.value);
  const upperVal = parseInt(upperSlider.value);
  
  upperSlider.oninput = function () {
      lowerVal = parseInt(lowerSlider.value);
      upperVal = parseInt(upperSlider.value);
  
      if (upperVal < lowerVal + 4) {
          lowerSlider.value = upperVal - 4;
          if (lowerVal == lowerSlider.min) {
          upperSlider.value = 4;
          }
      }
      document.querySelector('#ys-price-two').value=this.value
  };
  
  lowerSlider.oninput = function () {
      lowerVal = parseInt(lowerSlider.value);
      upperVal = parseInt(upperSlider.value);
      if (lowerVal > upperVal - 4) {
          upperSlider.value = lowerVal + 4;
          if (upperVal == upperSlider.max) {
              lowerSlider.value = parseInt(upperSlider.max) - 4;
          }
      }
      document.querySelector('#ys-price-one').value=this.value
  };
}
