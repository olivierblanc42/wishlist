

function addinWishList() {


    const btns = document.querySelectorAll(".btn-wishlist");
    const url = wpData.apiUrl + 'toggle';
    btns.forEach((btn) => btn.addEventListener("click", async (event) => {
        const postId = btn.dataset.postId;
        try {
            if (!wpData.isLogin) {
                alert("Vous devez être connecté");
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
                alert("une erreur est survenue");
                return;

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


async function wishList() {

    const url = wpData.apiUrl + 'get';

    try {

        const response = await fetch(url, {
            method: "GET",
            headers: {
                "X-WP-Nonce": wpData.nonce,
            },


        });

        if (!response.ok) {
            // throw new Error(`Response status: ${response.status}`);
            // alert("Votre message");
        }

        const wishlist = await response.json();

    
        const btns = document.querySelectorAll(".btn-test");




    } catch (error) {
        console.log(error);

    }
}


// wichList()