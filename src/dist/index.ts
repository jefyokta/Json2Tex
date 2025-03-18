import katex from "katex";

const result = katex.renderToString(process.argv[2])

console.log(result)