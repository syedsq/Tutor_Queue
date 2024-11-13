
window.addEventListener('load', async function () {
  await Clerk.load()

  if (Clerk.user) {
    document.getElementById('app').innerHTML = `
      <div id="user-button"></div>
    `

    const userButtonDiv = document.getElementById('user-button')

    Clerk.mountUserButton(userButtonDiv)
  } else {
    document.getElementById('app').innerHTML = `
      <div id="sign-in"></div>
    `

    const signInDiv = document.getElementById('sign-in')

    Clerk.mountSignIn(signInDiv)
  }
})