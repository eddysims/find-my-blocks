/** @jsx jsx */
import { jsx } from "theme-ui";
import { Fragment } from "react";
import {
  LocalStorageAction,
  LocalStorageActionTypes,
  LocalStorageState,
} from "../App/localStorageReducer";

import {
  CheckboxControl,
  Panel,
  PanelBody,
  PanelRow,
  SelectControl,
  Button,
} from "@wordpress/components";

import * as styles from "./styles";

interface SettingsProps {
  state: LocalStorageState;
  onChange({ type, value }: LocalStorageAction): void;
}

export const Settings = ({ onChange, state }: SettingsProps) => {
  /**
   * We must check for window when using @wordpress/components
   */
  if (typeof window === "undefined") return <Fragment />;

  const alphabeticalOptions = [
    { value: "alphabetical-a-z", label: "Alphabetical (a-z)" },
    { value: "alphabetical-z-a", label: "Alphabetical (z-a)" },
  ];

  return (
    <Panel sx={styles.settings} header="Settings">
      <PanelBody>
        <PanelRow>
          <SelectControl
            label="Sort navigation by:"
            value={state.navOrder}
            options={[
              ...alphabeticalOptions,
              { value: "count-high-low", label: "Most popular" },
              { value: "count-low-high", label: "Least popular" },
            ]}
            onChange={(value: string) => handleChange("navOrder", value)}
          />
        </PanelRow>
        <PanelRow>
          <SelectControl
            label="Sort cards by:"
            value={state.cardOrder}
            options={[
              ...alphabeticalOptions,
              { value: "count-high-low", label: "Most popular" },
              { value: "reusable", label: "Reusable first" },
              { value: "nested", label: "Nested first" },
            ]}
            onChange={(value: string) => handleChange("cardOrder", value)}
          />
        </PanelRow>
      </PanelBody>

      <PanelBody title="Show/hide blocks" icon="visibility">
        <PanelRow>
          <CheckboxControl
            label="Show core blocks"
            checked={state.showCoreBlocks}
            onChange={(value: boolean) => handleChange("showCoreBlocks", value)}
          />
        </PanelRow>
      </PanelBody>

      <PanelBody title="Advanced Settings" icon="admin-generic">
        <PanelRow sx={styles.help}>
          <CheckboxControl
            label="Include drafts in query"
            help="Warning: Enabling this feature could slow down the loading of the plugin depending on how many drafts your website has."
            onChange={() => console.log("change")}
          />
        </PanelRow>
        <PanelRow>
          <Button isPrimary onClick={handleClick} sx={{ mt: 2 }}>
            Save Settings
          </Button>
        </PanelRow>
      </PanelBody>
    </Panel>
  );

  function handleChange(
    type: LocalStorageActionTypes,
    value: string | boolean
  ) {
    onChange({ type, value });
  }

  function handleClick() {
    alert("save options");
  }
};
