// Optional: Sidebar toggle for mobile
const sidebar = document.querySelector(".sidebar");
const toggleBtn = document.createElement("button");
toggleBtn.textContent = "☰";
toggleBtn.className = "toggle-btn";
document.body.prepend(toggleBtn);

toggleBtn.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});
