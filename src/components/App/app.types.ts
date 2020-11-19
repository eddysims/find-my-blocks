export interface Post {
  readonly id: string;
  readonly title: string;
  readonly postType: string;
  readonly edit_url: string;
  readonly post_url: string;
  readonly isReusable: boolean;
  readonly isNested: boolean;
  readonly nestedBlockType: string;
  readonly count: number;
}

export interface Block {
  readonly name: string;
  posts: Array<Post>;
}

export type SortOrder =
  | "alphabetical-a-z"
  | "alphabetical-z-a"
  | "count-high-low"
  | "count-low-high"
  | "reusable"
  | "nested";
