const R = require("request-promise-native");
const cheerio = require("cheerio");
const fs = require("fs");
const path = require("path");

const args = process.argv;
const urlIndex = args.indexOf("--url");
if (urlIndex == -1) {
  console.log("Please pass --url");
  process.exit(0);
}
let url = args[urlIndex + 1];

(async function() {
  const $$ = await R({
    url,
    transform: function(body) {
      return cheerio.load(body);
    }
  });
  const hrefs = $$(".wpimg").each((index, a) => {
    const url = $$(a).attr("src");
    console.log(`dowloading ${url}`);
    const x = `https://wallpapercave.com/${url}`;
    // console.log(x);
    const file = fs.createWriteStream(
      path.resolve("./dist", path.basename(url))
    );
    R.get(x)
      .pipe(file)
      .on("finish", () => console.log(`downloaded ${url}`));
  });
})();
