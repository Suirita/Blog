import type { Category } from './Category';
import type { Comment } from './Comment';
import type { User } from './User';

export interface Article {
  id: number;
  title: string;
  content: string;
  views: number;
  user: User;
  category: Category;
  comments: Comment[];
}
