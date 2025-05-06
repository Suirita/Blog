export interface Comment {
  id: number;
  content: string;
  user: {
    id: number;
    name: string;
  };
  article: {
    id: number;
    title: string;
  };
}
