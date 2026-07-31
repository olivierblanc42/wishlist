
function createmodal() {

    if (document.querySelector(".modal-login-required")) {
        return;
    }

    const modal = document.createElement("div");
    modal.classList.add("modal-login-required");

    const modalContent = document.createElement("div");
    modalContent.classList.add("modal-login-required_content");

    modal.append(modalContent)

    const modalLogin = document.createElement("div");
    modalLogin.classList.add("modal-login");

    modalContent.append(modalLogin)

    const h3 = document.createElement("h3");
    const newContent = document.createTextNode("Log In & Save Your Wishlist");
    h3.appendChild(newContent);

    const span = document.createElement("span");
    const close = document.createTextNode("x");
    span.appendChild(close);


    const p = document.createElement("p");
    modalLogin.append(h3, span, p)

    h3.classList.add("modal-title");
    span.classList.add("close-require");

    const a = document.createElement("a");
    a.textContent = "Log in";
    a.href = wpData.loginUrl

    const text = document.createTextNode("Log in to add items to your wishlist.");

    p.append(a, text);
    document.body.append(modal);
}



function initLoginModal() {
    if (!wpData.isLogin) {
        createmodal();
    }

    const modal = document.querySelector(".modal-login-required");


    if (!modal) {
        return;
    }

    const body = document.body;
  
    const closeBtn = document.querySelector(".close-require");

    document.querySelectorAll(".wishlist-button-login-required").forEach((btn) => {
        btn.addEventListener("click", () => {
            modal.classList.add("active");
            body.style.overflow = "hidden";
        });
    });

    closeBtn.addEventListener("click", () => {
        modal.classList.remove("active");
        body.style.overflow = "";
        });

    modal.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.remove("active");
            body.style.overflow = "scroll";
        }
    });
}

initLoginModal();


function addinWishList() {


    const btns = document.querySelectorAll(".btn-wishlist");
    const url = wpData.apiUrl + 'toggle';
    btns.forEach((btn) => btn.addEventListener("click", async () => {
        const postId = btn.dataset.postId;
        try {
            if (!wpData.isLogin) {
                showLoginModal()
                return;
            }
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-WP-Nonce": wpData.nonce,
                    "Content-Type": "application/json",
                },

                body: JSON.stringify({ postId }),
            });



            if (!response.ok) {

                return
            }

            if (!btn.classList.contains('active')) {
                btn.classList.add('active')
            } else {
                btn.classList.remove('active')
            }
        } catch (error) {
            console.log(error);

        }

    }));

}

addinWishList()






function showLoginModal() {
    const modal = document.querySelector(".modal-login-required");

    if (modal) {
        modal.classList.add("active");
        document.body.style.overflow = "hidden";
    }
}
