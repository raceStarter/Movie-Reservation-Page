const loginBtn = document.getElementById("login_btn");
const logoutBtn = document.getElementById("logOut_btn");
const modal = document.getElementById("modal");
const submitBtn = document.getElementById("submit_btn");
const signInBtn = document.getElementById("signIn_btn");
const bg = document.getElementById("bg");

function loginPopUp() {
  bg.style.display = "block";

  modal.style.position = "fixed";
  modal.style.display = "block";
  modal.style.zIndex = "9999";
  modal.style.top = "50px";
  modal.style.right = "50px";
}

function inputValidation(id, pw) {
  const regForId = /^([A-Za-z0-9]){6,15}$/;
  const regForPw = /^.*(?=^.{8,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/;
  if (id === "" || pw === "" || !regForId.test(id) || !regForPw.test(pw)) {
    alert("아이디 또는 패스워드의 입력양식을 체크해주세요.");
    return false;
  }
  return true;
}

function signUp() {
  const id = document.getElementById("id_box").value;
  const pw = document.getElementById("password_box").value;
  if (!inputValidation(id, pw)) {
    closeModal();
    return;
  }

  $.post(
    "signUp.php",
    {
      name: id,
      password: pw,
    },
    function (data) {
      if (data === "fail") {
        alert("이미 사용중인 아이디입니다.");
      } else {
        alert("회원가입이 완료되었습니다.");
      }
    }
  );
}

function login() {
  const id = document.getElementById("id_box").value;
  const pw = document.getElementById("password_box").value;
  if (!inputValidation(id, pw)) {
    closeModal();
    return;
  }

  $.post(
    "login.php",
    {
      name: id,
      password: pw,
    },
    function (data) {
      if (data === "fail") {
        alert(
          "등록된 회원이 아니거나, 아이디 또는 비밀번호가 일치하지 않습니다."
        );
        closeModal();
      } else {
        const userId = document.createTextNode(data);
        document.getElementById("signed_id").appendChild(userId);
        loginBtn.style.display = "none";
        logoutBtn.style.display = "inline";
        closeModal();
      }
    }
  );
}

function logout() {
  const idBox = document.getElementById("signed_id");
  const rTable = document.getElementById("result_table");
  const rBox = document.getElementById("result");
  $.get("logout.php", function (data) {
    if (data == "logout") {
      alert("로그아웃 되었습니다.");
      idBox.removeChild(idBox.firstChild);
      logoutBtn.style.display = "none";
      loginBtn.style.display = "inline";

      while (rTable.childNodes.length !== 2) {
        rTable.removeChild(rTable.lastChild);
      }

      while (rBox.childNodes.length !== 2) {
        rBox.removeChild(rBox.lastChild);
      }

      rTable.style.display = "none";
    }
  });
}

function closeModal() {
  modal.style.display = "none";
  bg.style.display = "none";
  document.getElementById("id_box").value = "";
  document.getElementById("password_box").value = "";
}

loginBtn.addEventListener("click", loginPopUp);

submitBtn.addEventListener("click", login);

signInBtn.addEventListener("click", signUp);

logoutBtn.addEventListener("click", logout);

window.addEventListener("click", (event) => {
  event.target === bg ? closeModal() : false;
});

window.onload = function () {
  $.get("loginCheck.php", function (data) {
    if (data != "none") {
      const userId = document.createTextNode(data);
      document.getElementById("signed_id").appendChild(userId);
      loginBtn.style.display = "none";
      logoutBtn.style.display = "inline";
    }
  });
};
