function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    // Toggle the class that controls sidebar visibility
    sidebar.classList.toggle("-translate-x-full");
}

document.getElementById("toggle-sidebar").addEventListener("click", function() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
  
    // Toggle the visibility of the sidebar
    sidebar.classList.toggle("hidden");
  
    // Adjust the main content's margin
    if (sidebar.classList.contains("hidden")) {
      mainContent.classList.remove("sidebar-open");
      mainContent.classList.add("sidebar-closed");
    } else {
      mainContent.classList.remove("sidebar-closed");
      mainContent.classList.add("sidebar-open");
    }
  });
  