const preTags = document.getElementsByTagName("pre");
for (const e of preTags) {
    e.innerText = e.innerText.trim();
}