document.querySelectorAll(".input-container").forEach((element) => {
    element.lastElementChild.addEventListener("focusin", () => {
      element.style.borderColor = "#ffc632"
      const altImg = element.querySelector("img").alt
  
      element.firstElementChild.src = `images//${altImg + "-focus.svg"}`
    })
  
    element.lastElementChild.addEventListener("focusout", () => {
      element.style.borderColor = "#868686"
      const altImg = element.querySelector("img").alt
  
      element.firstElementChild.src = `images//${altImg + ".svg"}`
    })
  })