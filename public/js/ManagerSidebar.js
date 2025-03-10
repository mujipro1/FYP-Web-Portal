ManagerSidebar2 = `
    <div class="text-end">
    <button class="btn" onclick='RenderSideBar()'>
        <svg xmlns="http://www.w3.org/2000/svg" id="Bold" class='svg' viewBox="0 0 24 24" width="512" height="512"><path d="M14.121,12,18,8.117A1.5,1.5,0,0,0,15.883,6L12,9.879,8.11,5.988A1.5,1.5,0,1,0,5.988,8.11L9.879,12,6,15.882A1.5,1.5,0,1,0,8.118,18L12,14.121,15.878,18A1.5,1.5,0,0,0,18,15.878Z"/></svg>
    </button>
    </div>
    <ul>
        <li><a href="/manager/farms">Farms</a></li>
        <li><a href="/manager/render_cropexpense/${farm_id}">Expenses</a></li>
        <li><a href="/manager/sales/${farm_id}">Sales</a></li>
        <li><a href="/manager/render_workers/${farm_id}">Workers</a></li>
        <li><a href="/manager/lucifer/${farm_id}">Chacha</a></li>
    </ul>
`;

ManagerSidebar = `
<div class="text-center">
<button class="btn" onclick='RenderSideBar()'>
    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"  class='svg' viewBox="0 0 24 24" style='transform:rotate(90deg);'><path d="m3,1.5v21c0,.829-.672,1.5-1.5,1.5s-1.5-.671-1.5-1.5V1.5C0,.671.672,0,1.5,0s1.5.671,1.5,1.5ZM15.5,0c-.828,0-1.5.671-1.5,1.5v21c0,.829.672,1.5,1.5,1.5s1.5-.671,1.5-1.5V1.5c0-.829-.672-1.5-1.5-1.5Zm-7,0c-.828,0-1.5.671-1.5,1.5v21c0,.829.672,1.5,1.5,1.5s1.5-.671,1.5-1.5V1.5c0-.829-.672-1.5-1.5-1.5Z"/></svg>
</button>
</div>
`;

function RenderSideBar() {
    const sideBar = document.getElementsByClassName('sidebarcol')[0];
    const overlay = document.getElementById('overlay');
    
    if (sideBar.classList.contains('sidebarcol2')) {
        sideBar.classList.remove('sidebarcol2');
        overlay.classList.remove('active');
        document.getElementsByClassName('ManagerSidebar')[0].innerHTML = ManagerSidebar;
    } else {
        sideBar.classList.add('sidebarcol2');
        overlay.classList.add('active');
        document.getElementsByClassName('ManagerSidebar')[0].innerHTML = ManagerSidebar2;
    }
}

if (document.getElementsByClassName('ManagerSidebar')[0]) {
    document.getElementsByClassName('ManagerSidebar')[0].innerHTML = ManagerSidebar;
}

document.addEventListener('click', function(event) {
    const sideBar = document.getElementsByClassName('sidebarcol')[0];
    const overlay = document.getElementById('overlay');
    const isClickInsideSidebar = sideBar.contains(event.target);
    const isClickInsideButton = event.target.closest('button'); // Ensure the button is also excluded

    if (!isClickInsideSidebar && !isClickInsideButton) {
        if (sideBar.classList.contains('sidebarcol2')) {
            sideBar.classList.remove('sidebarcol2');
            overlay.classList.remove('active');
            document.getElementsByClassName('ManagerSidebar')[0].innerHTML = ManagerSidebar;
        }
    }
});
