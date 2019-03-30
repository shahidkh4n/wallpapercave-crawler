const puppeteer = require("puppeteer");
const download = require("download");
const args = process.argv;
const urlIndex = args.indexOf("--url");
if (urlIndex == -1) process.exit(0);
let url = args[urlIndex + 1];
(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  await page.goto(url);
  let images = await page.evaluate(() => {
    let urls = [];
    document.querySelectorAll("img.wpimg").forEach(item => {
      urls.push(item.src);
    });
    return urls;
  });
  await Promise.all(
    images.map(x => {
      download(x, "dist").then(() => console.log(`downloaded ${x}`));
    })
  );
  await browser.close();
})();
