import gulp, { dest } from "gulp";
import * as dartSass from "sass"; // Import the 'sass' package directly
import gulpSass from "gulp-sass"; // Import gulp-sass
import cleanCSS from "gulp-clean-css";
import yargs from "yargs";
import { hideBin } from "yargs/helpers";
import gulpif from "gulp-if";
import sourcemaps from "gulp-sourcemaps";
import imagemin from "gulp-imagemin";
import { deleteSync } from "del";
import webpack from "webpack-stream";
import WebpackObfuscator from "webpack-obfuscator";
import named from "vinyl-named";
import zip from "gulp-zip";
import replace from "gulp-replace";
import info from "./package.json" with { type: 'json' };;

//TODO css & js task could be deleted

const sass = gulpSass(dartSass);

const env = () => {
  const argv = yargs(hideBin(process.argv))
    .option("prod", {
      alias: "prod",
      type: "boolean",
      description: "Run in production mode",
      default: false,
    })
    .help()
    .parse(); // Parse the arguments right here

  return argv.prod;
};

//TODO: reduce src by using {} like for package
const paths = {
  styles: {
    src: ["src/assets/scss/bundle.scss"],
    dest: "dist/assets/css",
  },
  images: {
    src: [
      "src/assets/images/**/*.jpg",
      "src/assets/images/**/*.jpeg",
      "src/assets/images/**/*.png",
      "src/assets/images/**/*.svg",
      "src/assets/images/**/*.gif",
    ],
    dest: "dist/assets/images",
  },
  scripts: {
    src: ["src/assets/js/bundle.js"],
    dest: "dist/assets/js",
  },
  other: {
    src: [
      "src/assets/**/*",
      "!src/assets/images/**",
      "!src/assets/js/**",
      "!src/assets/scss/**",
    ],
    dest: "dist/assets",
  },
  package: {
    src: [
      "**/*",
      "!.vscode",
      "!node_modules{,/**}",
      "!packaged{,/**}",
      "!src{,/**}",
      "!babelrc",
      "!gitignore",
      "!gulpfile.babel.js",
      "!package.json",
      "!package-lock.json",
    ],
    dest: "packaged",
  },
};

const styles = (cb) => {
  const isProduction = env();

  return gulp
    .src(paths.styles.src)
    .pipe(gulpif(!isProduction, sourcemaps.init()))
    .pipe(sass().on("error", sass.logError))
    .pipe(gulpif(isProduction, cleanCSS({ compatibility: "ie8" })))
    .pipe(gulpif(!isProduction, sourcemaps.write()))
    .pipe(gulp.dest(paths.styles.dest))
    .on("end", cb);
};

const images = (cb) => {
  const isProduction = env();

  return gulp
    .src(paths.images.src, { encoding: false })
    .pipe(gulpif(isProduction, imagemin()))
    .pipe(gulp.dest(paths.images.dest))
    .on("end", cb);
};

const otherAssets = (cb) => {
  return gulp
    .src(paths.other.src, { encoding: false })
    .pipe(gulp.dest(paths.other.dest))
    .on("end", cb);
};

const clean = (cb) => {
  deleteSync(["dist"]);
  cb();
};

export const scripts = (cb) => {
  const isProduction = env();
  const plugins = [];

  if (isProduction) {
    plugins.push(
      new WebpackObfuscator({
        rotateStringArray: true,
      })
    );
  }

  return gulp
    .src(paths.scripts.src)
    .pipe(named())
    .pipe(
      webpack({
        mode: isProduction ? "production" : "development",
        module: {
          rules: [
            {
              test: /\.js$/,
              use: {
                loader: "babel-loader",
                options: {
                  presets: ["@babel/preset-env"],
                },
              },
            },
          ],
        },
        output: {
          filename: "[name].js",
        },
        devtool: !isProduction ? "inline-source-map" : false,
        plugins: plugins,
        externals: {
          jquery: "jQuery",
        },
      })
    )
    .pipe(gulp.dest(paths.scripts.dest))
    .on("end", cb);
};

//export theme zip file
export const compress = (cb) => {
  gulp
    .src(paths.package.src, { base: "../", encoding: false })
    .pipe(replace("_pluginname", info.name))
    .pipe(replace("_themename", info.theme))
    .pipe(zip(`${info.theme}-${info.name}.zip`))
    .pipe(gulp.dest(paths.package.dest))
    .on("end", cb);
};

export const watch = () => {
  gulp.watch(["src/assets/scss/**/*.scss", "includes/**/*.scss"], styles);
  gulp.watch(["src/assets/js/**/*.js", "includes/**/*.js"], scripts);
  gulp.watch(paths.images.src, images);
  gulp.watch(paths.other.src, otherAssets);
};

export const dev = gulp.series(
  clean,
  gulp.parallel(styles, scripts, images, otherAssets),
  watch
);

export const build = gulp.series(
  clean,
  gulp.parallel(styles, scripts, images, otherAssets),
  compress
);

export default dev;
