import katex from "katex";
const latex= process.argv.slice(2);
if (!latex) {
    process.exit(1)
}

try {
    const result = katex.renderToString(latex.join(" "), {
        throwOnError: false, 
    });

    console.log(result);
} catch (error) {
    process.exit(1);
}

