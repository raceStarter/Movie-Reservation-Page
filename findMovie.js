const inputBox = document.getElementById("input_box");
const searchBtn = document.getElementById("search_btn");
const resultBox = document.getElementById("result");
const modal2 = document.getElementById("modal2");
const reserveBtn = document.getElementById("reserve_btn");
const closeBtn = document.getElementById("close_btn");
const resultTable = document.getElementById("result_table");
const tableBox = document.getElementById("table_box");
const placeTable = document.getElementById("place_table");
const numOfpeople = document.getElementById("number");
const loginId = document.getElementById("signed_id");
let selectedMovieId = "";
let selectedScreenId = "";

function findMovie() {
  if (inputBox.value === "") {
    alert("검색어를 입력해주세요.");
    return;
  }

  while (resultTable.childNodes.length !== 2) {
    resultTable.removeChild(resultTable.lastChild);
  }

  while (resultBox.childNodes.length !== 2) {
    resultBox.removeChild(resultBox.lastChild);
  }

  const searchPlaceBtn = document.createElement("button");
  searchPlaceBtn.appendChild(document.createTextNode("상영관 검색하기"));
  searchPlaceBtn.classList.add("bottom_btn");
  searchPlaceBtn.addEventListener("click", findPlace);
  resultBox.appendChild(searchPlaceBtn);

  const input = inputBox.value;
  $.post(
    "searchMovie.php",
    {
      input_value: input,
    },
    function (data) {
      if (data === "no data") {
        const td = document.createElement("td");
        td.colSpan = 6;
        const result = document.createElement("p");
        result.appendChild(
          document.createTextNode("등록된 영화 정보가 없습니다.")
        );
        td.appendChild(result);
        resultTable.appendChild(td);
        resultTable.style.display = "table";
      } else if (isJsonString(data)) {
        const retArr = JSON.parse(data);

        if (Object.keys(retArr).length === 0) {
          const td1 = document.createElement("td");
          td1.colSpan = 6;
          const result1 = document.createElement("p");
          result1.appendChild(
            document.createTextNode("등록된 영화 정보가 없습니다.")
          );
          td1.appendChild(result1);
          resultTable.appendChild(td1);
          return;
        }

        const newRow = makeRowForMovieInfo();
        for (obj of retArr) {
          const nrw = newRow.cloneNode(true);
          const newTd = document.createElement("td");
          const preview = document.createElement("a");
          preview.appendChild(document.createTextNode("미리보기"));
          preview.target = "_blank";

          for (key in obj) {
            if (key === "file_name") {
              preview.href = `uploads/${obj[key]}`;
              const ntd = newTd.cloneNode();
              ntd.appendChild(preview);
              nrw.appendChild(ntd);
            } else if (key === "id") {
              const selector = nrw.firstChild.firstChild;
              selector.value = obj[key];
              selector.addEventListener("click", () => {
                selectedMovieId = selector.value;
              });
            } else if (key !== "id") {
              const ntd = newTd.cloneNode();
              ntd.appendChild(document.createTextNode(obj[key]));
              nrw.appendChild(ntd);
            }
          }
          resultTable.appendChild(nrw);
          resultTable.style.display = "table";
          searchPlaceBtn.style.display = "block";
        }
      } else {
        const result = document.createElement("p");
        result.appendChild(
          document.createTextNode("검색 중 오류가 발생했습니다.")
        );
        resultBox.appendChild(result);
        resultTable.style.display = "none";
      }
    }
  );
}

function findPlace() {
  if (selectedMovieId === "") {
    alert("상영관을 검색할 영화 정보를 선택해주세요.");
    return;
  }

  while (placeTable.childNodes.length !== 2) {
    placeTable.removeChild(placeTable.lastChild);
  }

  while (tableBox.childNodes.length !== 2) {
    tableBox.removeChild(tableBox.lastChild);
  }

  $.post(
    "findPlace.php",
    {
      movie_id: selectedMovieId,
    },
    function (data) {
      if (data === "no data") {
        const td2 = document.createElement("td");
        td2.colSpan = 4;
        const result = document.createElement("p");
        result.appendChild(
          document.createTextNode("등록된 상영 정보가 없습니다.")
        );
        td2.appendChild(result);
        placeTable.appendChild(td2);
        findPlacePopUp();
      } else if (isJsonString(data)) {
        const retArr = JSON.parse(data);

        if (Object.keys(retArr).length === 0) {
          const result = document.createElement("p");
          result.appendChild(
            document.createTextNode("등록된 상영 정보가 없습니다.")
          );
          tableBox.appendChild(result);
          findPlacePopUp();
          return;
        }

        const newRow = makeRowForPlaceInfo();
        for (obj of retArr) {
          const nrw = newRow.cloneNode(true);
          const newTd = document.createElement("td");

          for (key in obj) {
            if (
              key === "date" ||
              key === "screening_id" ||
              key === "reserve_seat"
            ) {
              const ntd = newTd.cloneNode();
              ntd.appendChild(document.createTextNode(obj[key]));
              nrw.appendChild(ntd);
            } else if (key === "id") {
              const selector = nrw.firstChild.firstChild;
              selector.value = obj[key];
              selector.addEventListener("click", () => {
                selectedScreenId = selector.value;
              });
            }
          }
          placeTable.appendChild(nrw);
          findPlacePopUp();
        }
      } else {
        const result = document.createElement("p");
        result.appendChild(
          document.createTextNode("검색 중 오류가 발생했습니다.")
        );
        tableBox.appendChild(result);
        findPlacePopUp();
      }
    }
  );
}

function isJsonString(str) {
  try {
    let json = JSON.parse(str);
    return typeof json === "object";
  } catch (e) {
    return false;
  }
}

function makeRowForMovieInfo() {
  const row = document.createElement("tr");
  const td = document.createElement("td");
  const radio = document.createElement("input");
  radio.type = "radio";
  radio.name = "selected_movie";
  td.appendChild(radio);
  row.appendChild(td);

  return row;
}

function makeRowForPlaceInfo() {
  const row = document.createElement("tr");
  const td = document.createElement("td");
  const radio = document.createElement("input");
  radio.type = "radio";
  radio.name = "selected_place";
  td.appendChild(radio);
  row.appendChild(td);

  return row;
}

function findPlacePopUp() {
  modal2.style.position = "fixed";
  modal2.style.display = "block";
  modal2.style.zIndex = "9999";
  modal2.style.top = "50%";
  modal2.style.right = "50%";
  modal2.style.transform = "translate(50%, -50%)";
}

function closePopUp() {
  modal2.style.display = "none";
  document.getElementById("number").value = "";
  selectedScreenId = "";
}

function reserveMovie() {
  const num = numOfpeople.value;

  if (selectedScreenId === "") {
    alert("예약할 상영 정보를 선택해주세요.");
    return;
  } else if (num === "") {
    alert("예약할 인원을 입력해주세요.");
    return;
  } else if (num < 1 || num > 10) {
    alert("한 번에 예약 가능한 인원은 1 ~ 10명입니다.");
    return;
  }

  $.get("loginCheck.php", function (data) {
    if (data === "none") {
      alert("로그인 후 영화 예약이 가능합니다.");
      return;
    } else {
      $.post(
        "reserve.php",
        {
          member_id: data,
          selected_movie: selectedMovieId,
          selected_screen: selectedScreenId,
          reserved_num: num,
        },
        function (data) {
          if (data == "no data") {
            alert("등록된 상영 정보가 없습니다.");
          } else if (data == "full") {
            alert("하나의 상영관의 최대 수용 인원은 20명까지입니다.");
          } else if (data == "success") {
            alert("예약되었습니다.");
            selectedScreenId = "";
            numOfpeople.value = "";
            modal2.style.display = "none";
          } else {
            alert("예약에 실패했습니다.");
          }
        }
      );
    }
  });
}

searchBtn.addEventListener("click", findMovie);
closeBtn.addEventListener("click", closePopUp);
reserveBtn.addEventListener("click", reserveMovie);
