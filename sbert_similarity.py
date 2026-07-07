import sys
import json
import os

# Suppress warnings from transformers/torch/etc.
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
import warnings
warnings.filterwarnings("ignore")

def main():
    try:
        # Read JSON from stdin
        input_data = json.loads(sys.stdin.read())
        query = input_data.get('query', '')
        candidates = input_data.get('candidates', [])

        if not query or not candidates:
            print(json.dumps([]))
            return

        # Import sentence_transformers here to speed up script if invalid inputs
        from sentence_transformers import SentenceTransformer, util

        # Load the SBERT model (will be downloaded on first run or read from cache)
        model = SentenceTransformer('all-MiniLM-L6-v2')

        # Encode query and candidates
        texts = [query] + [c['text'] for c in candidates]
        embeddings = model.encode(texts, convert_to_tensor=True, show_progress_bar=False)

        query_embedding = embeddings[0]
        candidate_embeddings = embeddings[1:]

        # Calculate cosine similarities
        cosine_scores = util.cos_sim(query_embedding, candidate_embeddings)[0]

        results = []
        for i, candidate in enumerate(candidates):
            score = float(cosine_scores[i].item())
            # Scale score to percentage, bounding between 0 and 100
            score_percent = round(max(0.0, min(1.0, score)) * 100, 2)
            results.append({
                'id': candidate['id'],
                'score': score_percent
            })

        print(json.dumps(results))
    except Exception as e:
        print(json.dumps({'error': str(e)}), file=sys.stderr)
        sys.exit(1)

if __name__ == '__main__':
    main()
