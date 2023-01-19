const moreActor = document.getElementById("more");
const addActorBtn = document.getElementById("add-actor-btn");
const delActorBtn = document.getElementById("del-actor-btn");
const showDate = document.getElementById("show-date");
const place1 = document.getElementById("place1");
const place2 = document.getElementById("place2");
const place3 = document.getElementById("place3");
const searchCinemaBtn = document.getElementById("search-cinema-btn");
const addShowInfoBtn = document.getElementById("add-show-info-btn");
const addedList = document.getElementById("added-list");

function makeInputBox() {
  const inputBox = document.createElement("input");
  inputBox.type = "text";
  inputBox.name = `actor${moreActor.childNodes.length + 1}`;
  inputBox.classList.add("actor");
  moreActor.appendChild(inputBox);
}

function searchingCinema() {
  const date = showDate.value;
  const title = document.getElementById("title").value;

  if (date === "") {
    alert("상영 날짜를 선택하십시오.");
    return;
  }

  $.post(
    "findScreeningInfo.php",
    {
      date: date,
      title: title,
    },
    function (data) {
      if (data === "already added") {
        alert("같은 날짜에 이미 추가되었습니다.");
        disableRadioItems();
        addShowInfoBtn.disabled = true;
        return;
      } else if (data === "") {
        enableRadioItems();
      } else {
        const returnArr = data.split("|");
        enableRadioItems();

        for (let i = 0; i < returnArr.length - 1; i++) {
          if (returnArr[i] === place1.value) {
            place1.disabled = true;
          } else if (returnArr[i] === place2.value) {
            place2.disabled = true;
          } else if (returnArr[i] === place3.value) {
            place3.disabled = true;
          }
        }
      }
      addShowInfoBtn.disabled = false;
    }
  );
}

function addInfo() {
  const date = showDate.value;
  let place;

  if (addedList.hasChildNodes()) {
    for (val of addedList.childNodes) {
      if (val.value.includes(date)) {
        alert("같은 날짜에 이미 추가되었습니다.");
        addShowInfoBtn.disabled = true;
        disableRadioItems();
        clearRadioItems();
        showDate.value = "";
        return;
      }
    }
  }

  if (date)
    if (place1.checked) {
      place = place1.value;
    } else if (place2.checked) {
      place = place2.value;
    } else if (place3.checked) {
      place = place3.value;
    } else {
      alert("상영관을 선택하십시오.");
      return;
    }

  const infoBox = document.createElement("input");
  infoBox.type = "text";
  infoBox.value = date + "," + place;
  infoBox.name = `showInfo${addedList.childNodes.length}`;
  infoBox.classList.add("infoBox");
  addedList.appendChild(infoBox);
  addShowInfoBtn.disabled = true;
  disableRadioItems();
  clearRadioItems();
  showDate.value = "";
}

function disableRadioItems() {
  place1.disabled = true;
  place2.disabled = true;
  place3.disabled = true;
}

function enableRadioItems() {
  place1.disabled = false;
  place2.disabled = false;
  place3.disabled = false;
}

function clearRadioItems() {
  place1.checked = false;
  place2.checked = false;
  place3.checked = false;
}

addActorBtn.addEventListener("click", (event) => {
  event.preventDefault();
  if (moreActor.childNodes.length < 2) {
    makeInputBox();
  }
});

delActorBtn.addEventListener("click", (event) => {
  event.preventDefault();
  if (moreActor.hasChildNodes()) {
    moreActor.removeChild(moreActor.lastChild);
  }
});

searchCinemaBtn.addEventListener("click", (event) => {
  event.preventDefault();
  searchingCinema();
});

addShowInfoBtn.addEventListener("click", (event) => {
  event.preventDefault();
  addInfo();
});
