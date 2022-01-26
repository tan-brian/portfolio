//filtre recherche
let elCheckBoxs = document.querySelectorAll('input[type=checkbox]'),
    elDivs = document.querySelectorAll('.item-catalog'),
    btnReset = document.querySelector('button[type=reset]'),
    viewGrid = document.querySelector('.view-list'),
    listCatalog = document.querySelector('.list-catalog'),
    container = document.querySelector('.catalog-container');
    let tabFiltered = [];


btnReset.addEventListener('click', () => {
    elDivs.forEach(div =>{
            
        div.style.display = 'flex';
    
    })
    tabFiltered = [];
})

elCheckBoxs.forEach(checkBox => {

    checkBox.addEventListener('change', () => {
        if(checkBox.checked){
          
            elDivs.forEach(div => {
                let category = div.dataset.category.split(' ');
                //console.log(category)
                if(category.includes(checkBox.value) && !tabFiltered.includes(div)){ 
                    tabFiltered.push(div);
                }
            })
        }else {
            tabFiltered.forEach(element => {
                let category = element.dataset.category.split(' ');
                
                if(category.includes(checkBox.value)){
                    tabFiltered = tabFiltered.filter(item => item !== element)
                }
            })
        } 
     
        elDivs.forEach(div =>{
            if(tabFiltered.includes(div)){
                div.style.display = 'flex';
            }else{
                div.style.display = 'none';
            }

        })


        let checked = document.querySelectorAll('input[type=checkbox]:checked');
        if(tabFiltered.length == 0 && checked.length == 0){
            elDivs.forEach(div =>{
              
                    div.style.display = 'flex';
                
            })
        }

        if(tabFiltered. length == 0){  
            elDivs.forEach(elDiv => {
                if(elDiv.textContent.toLowerCase().includes(search.value.toLowerCase())){ 
                    elDiv.style.display = 'flex';
                }else{
                    elDiv.style.display = 'none';
                }
                
            })
        }else {
            tabFiltered.forEach(elDiv => {
                if(elDiv.textContent.toLowerCase().includes(search.value.toLowerCase())){ 
                    elDiv.style.display = 'flex';
                }else{
                    elDiv.style.display = 'none';
                }
                
            })
        }

        if(tabFiltered.length == 1 && viewGrid.classList.contains('view-grid')){
            wrapper.classList.add('one-item');
        }else {
            wrapper.classList.remove('one-item');
            
        }
        
        if (tabFiltered.length == 2 && viewGrid.classList.contains('view-grid')){
            wrapper.classList.add('two-items');
        }else {
            wrapper.classList.remove('two-items');      
        }


        let div = document.querySelectorAll('.item-catalog:not([style*="display: none;"])')
    if(div.length == 1 && viewGrid.classList.contains('view-grid')){
        wrapper.classList.add('one-item');
        
    }else {
        wrapper.classList.remove('one-item');
    }
    
    if (div.length == 2 &&  viewGrid.classList.contains('view-grid')){
        wrapper.classList.add('two-items');
        
    }else {
        wrapper.classList.remove('two-items');  
    }

    if(viewGrid.classList.contains('view-grid')){
        if( div.length != 0 && window.innerWidth > 650 && div.length < 3){
    container.style.width = 'max-content';
  }else {
  container.style.width = '100%';
  }
    }
    })
})
// prix slider
const price =  document.getElementById('price-slider-paragraph')
      const progress = document.getElementById('price-progress');
        let list = document.querySelector('.list-catalog')
      const slider = () => {
        const min = Math.min(range[0].value, range[1].value);
        const max = Math.max(range[0].value, range[1].value);
        price.innerHTML = ` ${min} $ -  ${max} $`;
        progress.style.setProperty('--max', max);
        progress.style.setProperty('--min', min);
      }

      const range = list.querySelectorAll('input[type=range]')

      range.forEach((r)=>{
        r.addEventListener('mousemove', ()=>{
          slider();
        })
      })

      slider();


      const sidebarprice =  document.getElementById('sidebar-price-slider-paragraph')
      const sidebarprogress = document.getElementById('sidebar-price-progress');
        let sidebar = document.querySelector('.sidebar')
      const sidebarslider = () => {
        const min = Math.min(sidebarrange[0].value, sidebarrange[1].value);
        const max = Math.max(sidebarrange[0].value, sidebarrange[1].value);
        sidebarprice.innerHTML = ` ${min} $ -  ${max} $`;
        sidebarprogress.style.setProperty('--max', max);
        sidebarprogress.style.setProperty('--min', min);
      }

      const sidebarrange = sidebar.querySelectorAll('input[type=range]')

      sidebarrange.forEach((r)=>{
        r.addEventListener('mousemove', ()=>{
            sidebarslider();
        })
      })

      sidebarslider();
let elBtnPrix = document.querySelector('[data-js-prix]');

elBtnPrix.addEventListener('click', (e =>{
    e.preventDefault();
}))


var wrapper = document.getElementById("wrapper");
let btnList = document.querySelector('.list');
let btnGrid = document.querySelector('.grid')
var li = wrapper.querySelectorAll("li");
btnList.addEventListener("click", e => {
    if(window.innerWidth > 1700)
    container.style.width = '70%';
btnList.style.backgroundColor = "#600009";
btnGrid.style.backgroundColor = "#777586";
  // List view
  e.preventDefault();
  if(window.innerWidth > 650){
  wrapper.classList.add("list");
    wrapper.classList.remove("one-item");
    wrapper.classList.remove("two-items");
    li.forEach(li => {
        li.classList.remove('view-grid');
    })
  }
});

btnGrid.addEventListener("click", e => {

    btnGrid.style.backgroundColor = "#600009";
    btnList.style.backgroundColor = "#777586";
  // List view
  e.preventDefault();
  if(tabFiltered.length != 0  && tabFiltered.length < 3){
    container.style.width = 'max-content';
  }else {
  container.style.width = '100%';
  }
  if(tabFiltered.length == 1){
    wrapper.classList.add('one-item');

  }else if(tabFiltered.length == 2){
    wrapper.classList.add('two-items');
  }
  wrapper.classList.remove("list");
    li.forEach(li => {
        li.classList.add('view-grid');
    })

    let div = document.querySelectorAll('.item-catalog:not([style*="display: none;"])')
    if(div.length == 1 && viewGrid.classList.contains('view-grid')){
        wrapper.classList.add('one-item');
    }else {
        wrapper.classList.remove('one-item');
        
    }
    
    if (div.length == 2 &&  viewGrid.classList.contains('view-grid')){
        wrapper.classList.add('two-items');
    }else {
        wrapper.classList.remove('two-items');      
    }
});


let sections = document.querySelectorAll('[data-js-section]');

    sections.forEach(section => {
        let elChevron = section.querySelector('.chevron-btn'),
			elDetail = section.querySelector('[data-js-detail]');
            	// https://css-tricks.com/using-css-transitions-auto-dimensions/
		elChevron.addEventListener('click', () => {
			elDetail.classList.toggle('hide');
			elChevron.classList.toggle('bottom');
		})
    })


let search = document.querySelector('[data-js-search]');

search.addEventListener('input', () => {
    if(tabFiltered. length == 0){  
        elDivs.forEach(elDiv => {
            if(elDiv.textContent.toLowerCase().includes(search.value.toLowerCase())){ 
                elDiv.style.display = 'flex';
            }else{
                elDiv.style.display = 'none';
            }
            
        })
    }else {
        tabFiltered.forEach(elDiv => {
            if(elDiv.textContent.toLowerCase().includes(search.value.toLowerCase())){ 
                elDiv.style.display = 'flex';
            }else{
                elDiv.style.display = 'none';
            }
            
        })
    }
    let div = document.querySelectorAll('.item-catalog:not([style*="display: none;"])')
    if(div.length == 1 && viewGrid.classList.contains('view-grid')){
        wrapper.classList.add('one-item');
    }else {
        wrapper.classList.remove('one-item');
        
    }
    
    if (div.length == 2 &&  viewGrid.classList.contains('view-grid')){
        wrapper.classList.add('two-items');
    }else {
        wrapper.classList.remove('two-items');      
    }
})

window.addEventListener('DOMContentLoaded', ()=>{
    if(window.innerWidth < 650){
        wrapper.classList.remove("list");
       
        li.forEach(li => {
          li.classList.add('view-grid');
      })
    }else {
        wrapper.classList.add("list");
     
        li.forEach(li => {
          li.classList.remove('view-grid');
      })
    }
  });

  window.addEventListener('resize', ()=>{
    if(window.innerWidth < 650){
        wrapper.classList.remove("list");
       
        li.forEach(li => {
          li.classList.add('view-grid');
      })

      container.style.width = '100%';
      wrapper.classList.remove('two-items'); 
      wrapper.classList.remove('one-items'); 
    
     
      
        btnGrid.style.backgroundColor = "#600009";
        btnList.style.backgroundColor = "#777586";
        btnList.style.pointerEvents = "none";
        btnGrid.style.pointerEvents = "none";
    }
    else {
        btnList.style.pointerEvents = "all";
        btnGrid.style.pointerEvents = "all";

        let div = document.querySelectorAll('.item-catalog:not([style*="display: none;"])')
        if(div.length == 1) {
          container.style.width = 'max-content';
          wrapper.classList.add('one-items'); 
        }

        if (div.length == 2) {
          container.style.width = 'max-content';
          wrapper.classList.add('two-items')
       }
    }
  });


 