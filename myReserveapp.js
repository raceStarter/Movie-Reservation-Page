const cancelBtn = document.getElementById("cancel_btn");

function cancelReserve() {
  const checkBoxList = document.getElementsByTagName("input");
  const checkedList = [];
  const checkedIdList = [];

  for (checkbox of checkBoxList) {
    if (checkbox.checked) {
      checkedList.push(checkbox);
      checkedIdList.push(checkbox.parentNode.nextSibling.textContent);
    }
  }

  if (checkedList.length === 0) {
    alert("예약을 취소할 항목에 체크해주세요.");
    return;
  }

  $.post(
    "cancelReserve.php",
    {
      cancelList: JSON.stringify(checkedIdList),
    },
    function (data) {
      if (data == "success") {
        alert("예약이 취소되었습니다.");
        for (checkbox of checkedList) {
          checkbox.parentNode.parentNode.remove();
        }

        if (
          document.getElementById("list_table").firstChild.childNodes.length ===
          1
        ) {
          const td = document.createElement("td");
          td.colSpan = 6;
          const msg = document.createElement("p");
          msg.appendChild(document.createTextNode("예약된 내역이 없습니다."));
          msg.classList.add("notice");
          td.appendChild(msg);
          document.getElementById("list_table").appendChild(td);
          cancelBtn.style.display = "none";
        }
      } else {
        alert("예약 취소 도중 오류가 발생했습니다.");
      }
    }
  );
}

cancelBtn.addEventListener("click", cancelReserve);
