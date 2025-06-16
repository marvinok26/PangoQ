pipeline {
    agent any
    
    environment {
        // Docker Hub Configuration
        DOCKERHUB_USERNAME = 'your-dockerhub-username'  // Change this
        IMAGE_NAME = 'pangoq-app'
        IMAGE_TAG = "${BUILD_NUMBER}"
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-credentials')
    }
    
    stages {
        stage('🔍 Checkout') {
            steps {
                echo "🚀 Starting PangoQ CI/CD Pipeline"
                echo "📦 Build Number: ${BUILD_NUMBER}"
                echo "🌿 Branch: ${BRANCH_NAME}"
            }
        }
        
        stage('🧹 Cleanup') {
            steps {
                script {
                    sh '''
                        # Clean up old containers and images
                        docker system prune -f
                        docker container prune -f
                    '''
                }
            }
        }
        
        stage('🐳 Build Docker Image') {
            steps {
                script {
                    echo "🐳 Building Docker image..."
                    sh '''
                        # Build the Docker image
                        docker build -t ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:${IMAGE_TAG} .
                        docker tag ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:${IMAGE_TAG} ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:latest
                        
                        echo "✅ Docker image built successfully"
                        docker images | grep ${IMAGE_NAME}
                    '''
                }
            }
        }
        
        stage('🔐 Login to Docker Hub') {
            steps {
                script {
                    echo "🔐 Logging into Docker Hub..."
                    sh '''
                        echo "$DOCKERHUB_CREDENTIALS_PSW" | docker login -u "$DOCKERHUB_CREDENTIALS_USR" --password-stdin
                    '''
                }
            }
        }
        
        stage('📤 Push to Docker Hub') {
            steps {
                script {
                    echo "📤 Pushing images to Docker Hub..."
                    sh '''
                        # Push both tagged and latest versions
                        docker push ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:${IMAGE_TAG}
                        docker push ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:latest
                        
                        echo "✅ Images pushed to Docker Hub successfully"
                    '''
                }
            }
        }
        
        stage('🚀 Deploy (Optional)') {
            when {
                anyOf {
                    branch 'main'
                    branch 'master'
                }
            }
            steps {
                script {
                    echo "🚀 Deploying to production..."
                    sh '''
                        # Pull and run the latest image
                        docker pull ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:latest
                        
                        # Stop existing container if running
                        docker stop pangoq-production || true
                        docker rm pangoq-production || true
                        
                        # Run new container
                        docker run -d \
                            --name pangoq-production \
                            -p 8000:8000 \
                            -e APP_ENV=production \
                            -e DB_HOST=your-db-host \
                            -e DB_DATABASE=pango_q \
                            -e DB_USERNAME=root \
                            -e DB_PASSWORD=12345678 \
                            --restart unless-stopped \
                            ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:latest
                        
                        echo "✅ Deployment completed"
                    '''
                }
            }
        }
        
        stage('🔍 Health Check') {
            when {
                anyOf {
                    branch 'main'
                    branch 'master'
                }
            }
            steps {
                script {
                    echo "🔍 Running health check..."
                    sh '''
                        # Wait for application to start
                        sleep 30
                        
                        # Check if application is responding
                        curl -f http://localhost:8000/health || echo "Health check failed"
                    '''
                }
            }
        }
    }
    
    post {
        always {
            script {
                echo "🧹 Cleaning up..."
                sh '''
                    # Logout from Docker Hub
                    docker logout
                    
                    # Clean up local images to save space
                    docker image prune -f
                '''
            }
        }
        
        success {
            echo "✅ Pipeline completed successfully!"
            echo "🐳 Image available at: ${DOCKERHUB_USERNAME}/${IMAGE_NAME}:${IMAGE_TAG}"
        }
        
        failure {
            echo "❌ Pipeline failed!"
        }
    }
}