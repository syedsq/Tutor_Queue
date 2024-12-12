window.addEventListener("load", async () =>{
    await Clerk.load();
    if (!Clerk.user) {
        window.location.href = "./user-auth/signin.php";
    }
    else{
        const clerkUserId = Clerk.user.id;
        fetch('./user-auth/user_profile_complete.php',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id: clerkUserId })
        })
            .then(response => response.json())
            .then(data => {
                if(data.status === "error"){
                    window.location.href = "./user-auth/new_user_flow.php";
                }
            })
    }
})

