SERVICE :=template-service
ENVIRONMENT :=stage
PROJECT_ID := uc-next
REGISTRY := eu.gcr.io
ENV_UPDATE_DATE :=20.06.23
HELM :=3.6.2
HELM_CHART=frontend
PROJECT :=ucraft
HELM_PATH :=../ucraft-infrastructure/whitelabels/$(PROJECT)/3-products/ucraft
COMMIT_HASH := $(shell git rev-parse --short HEAD)
TAG := $(COMMIT_HASH)
DOCKERFILE=Dockerfile


ifeq ($(BUILD), jenkins)
	HELM_PATH=ucraft-infrastructure/whitelabels/$(PROJECT)/3-products/ucraft
endif

deploy:
	if gcloud container images describe $(REGISTRY)/$(PROJECT_ID)/$(SERVICE):$(ENVIRONMENT).$(TAG) --project $(PROJECT_ID) --format="value(name)" 2>/dev/null; then \
		echo "Image with tag $(TAG) already exists in the registry. Skipping build and push." ; \
		cd $(HELM_PATH)/values/$(SERVICE)/helm ;\
		helm upgrade --install $(SERVICE) -f values.$(ENVIRONMENT).yaml --set image.tag=$(ENVIRONMENT).$(TAG)  --set externalsecrets.updatedata=$(ENV_UPDATE_DATE)  \
		ucraft/$(HELM_CHART) --version $(HELM) \
		--namespace  $(PROJECT)-$(ENVIRONMENT)-app ; \
	else \
		docker build -f ${DOCKERFILE} . -t $(REGISTRY)/$(PROJECT_ID)/$(SERVICE):$(ENVIRONMENT).$(TAG) ; \
		docker push $(REGISTRY)/$(PROJECT_ID)/$(SERVICE):$(ENVIRONMENT).$(TAG) ; \
		cd $(HELM_PATH)/values/$(SERVICE)/helm ;\
		helm upgrade --install $(SERVICE) -f values.$(ENVIRONMENT).yaml --set image.tag=$(ENVIRONMENT).$(TAG)  --set externalsecrets.updatedata=$(ENV_UPDATE_DATE)  \
		ucraft/$(HELM_CHART) --version $(HELM) \
		--namespace  $(PROJECT)-$(ENVIRONMENT)-app ; \
	fi


build:
	if gcloud container images describe $(REGISTRY)/$(PROJECT_ID)/$(SERVICE):$(ENVIRONMENT).$(TAG) --project $(PROJECT_ID) --format="value(name)" 2>/dev/null; then \
			echo "Image with tag $(TAG) already exists in the registry. Skipping build and push." ; \
		else \
			docker build -f ${DOCKERFILE} . -t $(REGISTRY)/$(PROJECT_ID)/$(SERVICE):$(ENVIRONMENT).$(TAG) &&  \
			docker push $(REGISTRY)/$(PROJECT_ID)/$(SERVICE):$(ENVIRONMENT).$(TAG) ; \
	fi
	
release:
	cd $(HELM_PATH)/values/$(SERVICE)/helm && \
	helm upgrade --install $(SERVICE) -f values.$(ENVIRONMENT).yaml --set image.tag=$(ENVIRONMENT).$(TAG)  --set externalsecrets.updatedata=$(ENV_UPDATE_DATE)  \
	ucraft/$(HELM_CHART) --version $(HELM) \
	--namespace  $(PROJECT)-$(ENVIRONMENT)-app ; 

