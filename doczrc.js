import { createPlugin } from "docz-core";

const projectPlugin = () =>
  createPlugin({
    onCreateWebpackConfig: ({ stage, loaders, getConfig }) => {
      const config = getConfig();

      if (stage.includes("html")) {
        config.module.rules.push({
          test: /@wordpress\/components/,
          use: loaders.null(),
        });
      }
    },
  });

export default {
  title: "Find My Blocks",
  description:
    "Find My Blocks, a WordPress plugin to help finspecific Gutenberg blocks on your WordPress website.",
  typescript: true,
  port: 3339,
  src: "src",
  menu: ["Find My Blocks", "Design System", "Components"],
  plugins: [projectPlugin()],
};
